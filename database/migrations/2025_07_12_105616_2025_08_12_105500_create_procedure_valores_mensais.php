<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    DB::unprepared("
        DROP PROCEDURE IF EXISTS sp_gerar_fluxo_caixa_agregado;

        CREATE PROCEDURE sp_gerar_fluxo_caixa_agregado(
            IN p_empresa_id INT,
            IN p_data_inicial DATE,
            IN p_data_final DATE,
            IN p_fk_tipocategoria_id INT
        )
        BEGIN

            DECLARE v_display_mes_1 VARCHAR(15);
            DECLARE v_display_mes_2 VARCHAR(15);
            DECLARE v_display_mes_3 VARCHAR(15);
            DECLARE v_display_mes_4 VARCHAR(15);
            DECLARE v_display_mes_5 VARCHAR(15);
            DECLARE v_display_mes_6 VARCHAR(15);
            DECLARE v_display_mes_7 VARCHAR(15);
            DECLARE v_display_mes_8 VARCHAR(15);
            DECLARE v_display_mes_9 VARCHAR(15);
            DECLARE v_display_mes_10 VARCHAR(15);
            DECLARE v_display_mes_11 VARCHAR(15);
            DECLARE v_display_mes_12 VARCHAR(15);

            SET @@cte_max_recursion_depth = 100;

            SET v_display_mes_1 = DATE_FORMAT(p_data_inicial, '%b/%Y');
            SET v_display_mes_2 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 1 MONTH), '%b/%Y');
            SET v_display_mes_3 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 2 MONTH), '%b/%Y');
            SET v_display_mes_4 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 3 MONTH), '%b/%Y');
            SET v_display_mes_5 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 4 MONTH), '%b/%Y');
            SET v_display_mes_6 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 5 MONTH), '%b/%Y');
            SET v_display_mes_7 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 6 MONTH), '%b/%Y');
            SET v_display_mes_8 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 7 MONTH), '%b/%Y');
            SET v_display_mes_9 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 8 MONTH), '%b/%Y');
            SET v_display_mes_10 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 9 MONTH), '%b/%Y');
            SET v_display_mes_11 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 10 MONTH), '%b/%Y');
            SET v_display_mes_12 = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 11 MONTH), '%b/%Y');

 WITH RECURSIVE CategoryHierarchy AS (
        SELECT
            c.numero_categoria AS ancestor_id,
            c.numero_categoria AS descendant_id,
            c.nome AS descendant_name,
            c.categoria_pai AS descendant_parent_id,
            c.nivel AS descendant_level,
            c.fk_tipocategoria_id AS descendant_fk_tipocategoria_id
        FROM
            categorias c
        WHERE
            c.fk_tipocategoria_id = p_fk_tipocategoria_id
        UNION ALL
        SELECT
            ch.ancestor_id,
            c_child.numero_categoria,
            c_child.nome,
            c_child.categoria_pai,
            c_child.nivel,
            c_child.fk_tipocategoria_id
        FROM
            categorias c_child
        INNER JOIN
            CategoryHierarchy ch ON c_child.categoria_pai = ch.descendant_id
        WHERE
            c_child.fk_tipocategoria_id = p_fk_tipocategoria_id
    ),
    AggregatedTransactions AS (
        SELECT
            ch.ancestor_id AS category_id,
            DATE_FORMAT(l.data_lcto, '%Y-%m') AS transaction_year_month,
            SUM(l.valor) AS aggregated_monthly_value
        FROM
            lancamentos l
        INNER JOIN
            CategoryHierarchy ch ON l.categorias_id = ch.descendant_id
        WHERE
            l.empresa_id = p_empresa_id
            AND l.data_lcto BETWEEN p_data_inicial AND p_data_final
        GROUP BY
            ch.ancestor_id,
            transaction_year_month
    ),
    CategoriasComValores AS (
        SELECT
            c.numero_categoria AS id,
            c.nome AS nome_categoria,
            c.categoria_pai AS categoria_pai,
            c.nivel AS nivel,
            c.fk_tipocategoria_id,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(p_data_inicial, '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_1,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 1 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_2,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 2 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_3,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 3 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_4,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 4 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_5,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 5 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_6,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 6 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_7,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 7 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_8,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 8 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_9,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 9 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_10,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 10 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_11,
            COALESCE(SUM(CASE WHEN at.transaction_year_month = DATE_FORMAT(DATE_ADD(p_data_inicial, INTERVAL 11 MONTH), '%Y-%m') THEN at.aggregated_monthly_value ELSE 0 END), 0.00) AS mes_12
        FROM
            categorias c
        LEFT JOIN
            AggregatedTransactions at ON c.numero_categoria = at.category_id
        WHERE
            c.fk_tipocategoria_id = p_fk_tipocategoria_id
            AND c.nivel > 0
        GROUP BY
            c.numero_categoria, c.nome, c.categoria_pai, c.nivel, c.fk_tipocategoria_id
    )

    SELECT
        cv.id,
        cv.nome_categoria,
        cv.categoria_pai,
        cv.nivel,
        cv.fk_tipocategoria_id,
        cv.mes_1, cv.mes_2, cv.mes_3, cv.mes_4, cv.mes_5, cv.mes_6,
        cv.mes_7, cv.mes_8, cv.mes_9, cv.mes_10, cv.mes_11, cv.mes_12,
        v_display_mes_1 AS display_mes_1,
        v_display_mes_2 AS display_mes_2,
        v_display_mes_3 AS display_mes_3,
        v_display_mes_4 AS display_mes_4,
        v_display_mes_5 AS display_mes_5,
        v_display_mes_6 AS display_mes_6,
        v_display_mes_7 AS display_mes_7,
        v_display_mes_8 AS display_mes_8,
        v_display_mes_9 AS display_mes_9,
        v_display_mes_10 AS display_mes_10,
        v_display_mes_11 AS display_mes_11,
        v_display_mes_12 AS display_mes_12
    FROM
        CategoriasComValores cv
    HAVING (cv.mes_1 + cv.mes_2 + cv.mes_3 + cv.mes_4 + cv.mes_5 + cv.mes_6 +
            cv.mes_7 + cv.mes_8 + cv.mes_9 + cv.mes_10 + cv.mes_11 + cv.mes_12) <> 0
    ORDER BY
        cv.id;

        END;
    ");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       DB::unprepared("DROP PROCEDURE IF EXISTS sp_gerar_fluxo_caixa_agregado;");
    }
};
