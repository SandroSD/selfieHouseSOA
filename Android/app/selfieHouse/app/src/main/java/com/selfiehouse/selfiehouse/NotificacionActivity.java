package com.selfiehouse.selfiehouse;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.widget.ListView;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.selfiehouse.selfiehouse.Clases.Constantes;
import com.selfiehouse.selfiehouse.Clases.Notificacion;
import com.selfiehouse.selfiehouse.Clases.NotificacionAdapter;
import com.selfiehouse.selfiehouse.Servicios.NotificacionService;

import java.util.List;
import java.util.concurrent.TimeUnit;

import okhttp3.OkHttpClient;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class NotificacionActivity extends AppCompatActivity{

        private ListView listView;
        private List<Notificacion> listNotificacion;
        private NotificacionAdapter adapter;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_notificaciones);
        listView = (ListView) findViewById(R.id.lvNotificacion);
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




        /* Obtengo la cantidad de solicitudes*/
        NotificacionService servicioNotificacion = retrofit.create(NotificacionService.class);
        Call<List<Notificacion>> serviciosCall = servicioNotificacion.getNotificacion(true);
        serviciosCall.enqueue(new Callback<List<Notificacion>>() {
            @Override
            public void onResponse(Call<List<Notificacion>> call, Response<List<Notificacion>> response) {

                // Me traigo los datos de la bd y lso cargo en la lista
                listNotificacion = response.body();
                //cantidadDeSolicitudes = (TextView) findViewById(R.id.textCantidadDeSolicitudes);
                //cantidadDeSolicitudes.setText("Solicitudes: "+as.size());

                //Enlazo mi adaptador personalizado al listview
                adapter = new NotificacionAdapter(NotificacionActivity.this,R.layout.item_notificacion,listNotificacion);

                listView.setAdapter(adapter);

            }

            @Override
            public void onFailure(Call<List<Notificacion>> call, Throwable throwable) {
                System.out.println("Error: "+throwable.getMessage());
            }
        });

    }
}
