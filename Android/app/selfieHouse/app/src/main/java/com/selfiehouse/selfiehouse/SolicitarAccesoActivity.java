package com.selfiehouse.selfiehouse;

import android.Manifest;
import android.animation.Animator;
import android.animation.AnimatorListenerAdapter;
import android.annotation.TargetApi;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Color;
import android.location.Location;
import android.location.LocationManager;
import android.support.annotation.NonNull;
import android.support.design.widget.Snackbar;
import android.support.v4.app.ActivityCompat;
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
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.bumptech.glide.request.target.GlideDrawableImageViewTarget;
import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.Clases.Circulo;
import com.selfiehouse.selfiehouse.Clases.Constantes;
import com.selfiehouse.selfiehouse.Clases.Punto;
import com.selfiehouse.selfiehouse.Clases.Ubicacion;
import com.selfiehouse.selfiehouse.Servicios.AccesoSolicitudService;
import com.selfiehouse.selfiehouse.Servicios.UbicacionService;

import java.text.NumberFormat;
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
    private TextView tvLatitud, tvLongitud, tvAltura, tvPrecision;
    private LocationManager locManager;
    private Location loc;
    private TextView coordenadasDelSE, coordenadasDeAndroid;
    Punto puntoEmbebido, puntoAndroid ;
    Circulo circuloEmbebido,circuloAndroid;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_solicitar_acceso);
        coordenadasDelSE = (TextView) findViewById(R.id.textViewGPS);
        //coordenadasDeAndroid = (TextView) findViewById(R.id.textViewUbicacionActual);
        getWindow().getDecorView().setBackgroundColor(Color.WHITE);

        ImageView imageView = (ImageView) findViewById(R.id.imageViewMundo);
        GlideDrawableImageViewTarget imageViewTarget = new GlideDrawableImageViewTarget(imageView);
        Glide.with(this).load(R.drawable.world).into(imageViewTarget);




        /**
         * Obtengo la ubicacion del sistema embebido
         *
         */
        Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/ws/")
                .addConverterFactory(GsonConverterFactory.create())
                .build();

        if(obtenerCoordenadasAndroid()){

            UbicacionService servicioUbicacion = retrofit.create(UbicacionService.class);
            Call<Ubicacion> ubicacionCall = servicioUbicacion.getUbicacion(true);
            ubicacionCall. enqueue(new Callback<Ubicacion>() {
                @Override
                public void onResponse(Call<Ubicacion> call, Response<Ubicacion> response) {

                    double  longitudSE = response.body().getLongitud();
                    System.out.println("Sistema embebido: Latitud: "+response.body().getLatitud()+" - Longitud: "+response.body().getLongitud());

                    puntoEmbebido = new Punto (response.body().getLatitud(),response.body().getLongitud());
                    circuloEmbebido = new Circulo(puntoEmbebido,Constantes.RADIO_UBICACION);
                    System.out.println(circuloEmbebido.toString());

                    if(circuloAndroid.intersectaCon(circuloEmbebido)){
                        coordenadasDelSE.setText("Ubicación válida");
                        coordenadasDelSE.setTextColor(Color.GREEN);
                        Uri uri = Uri.parse("http://192.168.1.10:8080/selfiehouse");
                        Intent intent = new Intent(Intent.ACTION_VIEW, uri);
                        startActivity(intent);
                        //Toast.makeText(SolicitarAccesoActivity.this,"Intersectan",Toast.LENGTH_SHORT).show();
                    } else {
                        coordenadasDelSE.setText("Debe estar cerca de la casa para solicitar acceso");
                        coordenadasDelSE.setTextColor(Color.RED);
                        //Toast.makeText(SolicitarAccesoActivity.this, "No Intersectan", Toast.LENGTH_SHORT).show();

                        //coordenadasDeAndroid.setText(p1.toString());
                        System.out.println(circuloAndroid.toString());
                    }

                }

                @Override
                public void onFailure(Call<Ubicacion> call, Throwable throwable) {
                    coordenadasDelSE.setText("Error al obtener las coordenadas del servidor");
                    coordenadasDelSE.setTextColor(Color.RED);
                    return;
                }
            });

          } else {


        }





    }

    private boolean obtenerCoordenadasAndroid(){
        ActivityCompat.requestPermissions(SolicitarAccesoActivity.this,new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, 1);

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED)
        {
            coordenadasDelSE.setText("No se han definido los permisos necesarios.");
            coordenadasDelSE.setTextColor(Color.RED);
            //tvLatitud.setText("No se han definido los permisos necesarios.");
            //tvLongitud.setText("");

            return false;
        }
        else
        {
            locManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
            loc = locManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);

            while(loc == null){
                loc = locManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
                System.out.println("Intentando obtener ubicacion GPS");
                coordenadasDelSE.setTextColor(Color.BLUE);
            }

            if(loc == null)
            {
                coordenadasDelSE.setText("Error al obtener ubicacion de Android");
                coordenadasDelSE.setTextColor(Color.RED);
                return false;

            } else {
                 System.out.println(loc.getLongitude());
                 System.out.println(loc.getLatitude());

                puntoAndroid = new Punto (loc.getLatitude(),loc.getLongitude());
                circuloAndroid = new Circulo(puntoAndroid,Constantes.RADIO_UBICACION);
                return true;

            }



        }
    }



}

