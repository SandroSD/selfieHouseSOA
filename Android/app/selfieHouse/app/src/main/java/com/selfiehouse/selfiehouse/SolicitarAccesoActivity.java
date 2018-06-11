package com.selfiehouse.selfiehouse;

import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.annotation.TargetApi;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.support.annotation.NonNull;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.app.LoaderManager.LoaderCallbacks;

import android.content.CursorLoader;
import android.content.Loader;
import android.database.Cursor;
import android.net.Uri;
import android.os.AsyncTask;

import android.os.Build;
import android.os.Bundle;
import android.provider.ContactsContract;
import android.text.TextUtils;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.inputmethod.EditorInfo;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.Clases.Constantes;
import com.selfiehouse.selfiehouse.Clases.Ubicacion;
import com.selfiehouse.selfiehouse.Servicios.AccesoSolicitudService;
import com.selfiehouse.selfiehouse.Servicios.UbicacionService;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

import static android.Manifest.permission.READ_CONTACTS;

/**
 * A login screen that offers login via email/password.
 */
public class SolicitarAccesoActivity extends AppCompatActivity  {


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_solicitar_acceso);
        final EditText mEdit = (EditText) findViewById(R.id.textoVerificacionUbicacion);
        /**
         * Obtengo la ubicacion del sistema embebido
         *
         */
        Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/ws/")
                .addConverterFactory(GsonConverterFactory.create())
                .build();

        UbicacionService servicioUbicacion = retrofit.create(UbicacionService.class);
        Call<Ubicacion> ubicacionCall = servicioUbicacion.getUbicacion(true);
        ubicacionCall. enqueue(new Callback<Ubicacion>() {
            @Override
            public void onResponse(Call<Ubicacion> call, Response<Ubicacion> response) {

                double  longitudSE = response.body().getLongitud();


                mEdit.setText("Latitud: "+response.body().getLatitud()+" - Longitud: "+response.body().getLongitud());

                /**
                 *
                 * Aca deberia compararla contra la del GPS
                 */
                /*
                * si sale por verdadero
                * */
                //Uri uri = Uri.parse("http://192.168.1.10:8080/selfiehouse");
                //Intent intent = new Intent(Intent.ACTION_VIEW, uri);
                //startActivity(intent);

                /*
                * si sale por falso
                * */
                //mEdit.setText("Debe estar cerca de la casa para poder solicitar acceso");

            }

            @Override
            public void onFailure(Call<Ubicacion> call, Throwable throwable) {
                mEdit.setText("Hubo un error al obtener la ubicacion GPS");
            }
        });

        /*
        Uri uri = Uri.parse("http://192.168.1.10:8080/selfiehouse");
        Intent intent = new Intent(Intent.ACTION_VIEW, uri);
        startActivity(intent);
       */
    }




}

