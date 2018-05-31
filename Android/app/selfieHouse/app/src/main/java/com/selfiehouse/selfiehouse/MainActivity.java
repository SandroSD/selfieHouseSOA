package com.selfiehouse.selfiehouse;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import static android.view.View.*;

public class MainActivity extends AppCompatActivity {
    Button button_Cfg,button_Acc, button_SAcc, button_Ctrl;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);


        button_Acc = (Button)findViewById(R.id.btnAcceder);
        button_SAcc = (Button)findViewById(R.id.btnSolicitarAcceso);
        button_Ctrl = (Button)findViewById(R.id.btnControlar);
        button_Cfg = (Button)findViewById(R.id.btnConfiguracion);

        /* Click listener del boton Acceso */

        button_Acc.setOnClickListener(new OnClickListener() {

            public void onClick(View v){
                // Estoy en MainActivity.this y voy hacia MainActivity.class
                Intent accesoIntent = new Intent (MainActivity.this, AccesoActivity.class);
                startActivity(accesoIntent);
            }

        });

        /* Click listener del boton Solicitar Acceso */

        button_SAcc.setOnClickListener(new OnClickListener() {

            public void onClick(View v){
                // Estoy en MainActivity.this y voy hacia MainActivity.class
                Intent solicitarAccesoIntent = new Intent (MainActivity.this, SolicitarAccesoActivity.class);
                startActivity(solicitarAccesoIntent);
            }

        });

        /* Click listener del boton Controlar */

        button_Ctrl.setOnClickListener(new OnClickListener() {

            public void onClick(View v){
                // Estoy en MainActivity.this y voy hacia MainActivity.class
                Intent controlIntent = new Intent (MainActivity.this, ControlActivity.class);
                startActivity(controlIntent);
            }

        });




        /* Click listener del boton Configuracion */

        button_Cfg.setOnClickListener(new OnClickListener() {

            public void onClick(View v){
                // Estoy en MainActivity.this y voy hacia MainActivity.class
                Intent configuracionIntent = new Intent (MainActivity.this, ConfiguracionActivity.class);
                startActivity(configuracionIntent);
            }

        });


    }

    //Metodo que es llamada cuando se cierra la activity
    protected void onDestroy()
    {
        super.onDestroy();
        //Toast.makeText(getApplicationContext(),"Cerrando Inicio",Toast.LENGTH_LONG).show();
    }
}
