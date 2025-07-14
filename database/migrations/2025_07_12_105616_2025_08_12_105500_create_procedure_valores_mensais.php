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

            -- AQUI VAI TODO O RESTANTE DO CÓDIGO DA PROCEDURE COMO UMA ÚNICA STRING
            -- (CategoryHierarchy, AggregatedTransactions, etc.)

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
