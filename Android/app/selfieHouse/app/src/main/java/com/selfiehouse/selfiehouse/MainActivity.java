package com.selfiehouse.selfiehouse;

import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;

import static android.view.View.*;

public class MainActivity extends AppCompatActivity {
    ImageButton button_Cfg,button_Acc, button_SAcc, button_Ctrl;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);      // No permite que la activity se adapte a la rotacion de pantalla
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        getSupportActionBar().setDisplayShowTitleEnabled(false);    // Oculta el nombre de la APP
        getSupportActionBar().setIcon(R.mipmap.ic_launcher);        // Cambia el icono de la APP

        button_Acc = (ImageButton)findViewById(R.id.btnAcceder);
        button_SAcc = (ImageButton)findViewById(R.id.btnSolicitarAcceso);
        button_Ctrl = (ImageButton)findViewById(R.id.btnControlar);
        button_Cfg = (ImageButton)findViewById(R.id.btnConfiguracion);

        /* Click listener del boton Acceso */

        button_Acc.setOnClickListener(new OnClickListener() {

            public void onClick(View v){
                // Estoy en MainActivity.this y voy hacia MainActivity.class
                Intent accesoIntent = new Intent (MainActivity.this, AccesoActivity.class);
                accesoIntent.putExtra("tipoAcceso", "222");
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
                Intent controlIntent = new Intent (MainActivity.this, AccesoActivity.class);
                controlIntent.putExtra("tipoAcceso", "777");
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

    }
}
