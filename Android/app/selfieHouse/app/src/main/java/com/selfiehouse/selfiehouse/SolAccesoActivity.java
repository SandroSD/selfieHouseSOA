package com.selfiehouse.selfiehouse;


import android.content.Intent;

import android.os.Bundle;
import android.support.annotation.Nullable;

import android.support.v7.app.AppCompatActivity;
import android.view.View;

import android.widget.ImageButton;
import android.widget.ListView;
import android.widget.Toast;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.Clases.Constantes;
import com.selfiehouse.selfiehouse.Clases.SolAccAdapter;
import com.selfiehouse.selfiehouse.Servicios.AccesoSolicitudService;

import java.util.List;
import java.util.concurrent.TimeUnit;

import okhttp3.OkHttpClient;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class SolAccesoActivity extends AppCompatActivity {

    private ListView listView;
    private List<AccesoSolicitud> listSolicitud;
    private SolAccAdapter adapter;
    ImageButton button_Acesso;
    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_solicitud_acceso);

        button_Acesso = (ImageButton) findViewById(R.id.btnCamara);
        listView = (ListView) findViewById(R.id.lvSolicitud);
        final Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/ws/")
                //.baseUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfiehouse/Webserver/ws/")
                .addConverterFactory(GsonConverterFactory.create())
                .build();

        // Para setear Timeout
        OkHttpClient okHttpClient = new OkHttpClient().newBuilder()
                .connectTimeout(60, TimeUnit.SECONDS)
                .readTimeout(60,TimeUnit.SECONDS)
                .writeTimeout(60,TimeUnit.SECONDS)
                .build();

        Gson gson = new GsonBuilder()
                .setLenient()
                .create();


/*        button_Acesso.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v){
                Intent visorFotoIntent = new Intent (SolAccesoActivity.this, VisorActivity.class);

              //  Toast.makeText(SolAccesoActivity.this,"Id: " + listView.getItemAtPosition(listView.getId()),Toast.LENGTH_LONG).show();
                //visorFotoIntent.putExtra("id",)
                //startActivity(visorFotoIntent);
            }
        });*/


        /* Obtengo la cantidad de solicitudes*/
        AccesoSolicitudService servicioSolicitudAcceso = retrofit.create(AccesoSolicitudService.class);
        Call<List<AccesoSolicitud>> serviciosCall = servicioSolicitudAcceso.getAccesoSolicitud(true);
        serviciosCall.enqueue(new Callback<List<AccesoSolicitud>>() {
            @Override
            public void onResponse(Call<List<AccesoSolicitud>> call, Response<List<AccesoSolicitud>> response) {

                // Me traigo los datos de la bd y lso cargo en la lista
                listSolicitud = response.body();
                //cantidadDeSolicitudes = (TextView) findViewById(R.id.textCantidadDeSolicitudes);
                //cantidadDeSolicitudes.setText("Solicitudes: "+as.size());

                //Enlazo mi adaptador personalizado al listview
                adapter = new SolAccAdapter(SolAccesoActivity.this,R.layout.item_solicitud,listSolicitud);

                listView.setAdapter(adapter);

            }

            @Override
            public void onFailure(Call<List<AccesoSolicitud>> call, Throwable throwable) {
                System.out.println("Error: "+throwable.getMessage());
            }
        });
    }
}
