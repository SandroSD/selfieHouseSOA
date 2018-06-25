package com.selfiehouse.selfiehouse;

import android.Manifest;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Camera;
import android.graphics.Color;
import android.net.Uri;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.FragmentActivity;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.CameraPosition;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import com.selfiehouse.selfiehouse.Clases.Circulo;
import com.selfiehouse.selfiehouse.Clases.Constantes;
import com.selfiehouse.selfiehouse.Clases.Punto;
import com.selfiehouse.selfiehouse.Clases.Ubicacion;
import com.selfiehouse.selfiehouse.Servicios.UbicacionService;

import java.util.concurrent.TimeUnit;

import okhttp3.OkHttpClient;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class UbicacionActivity extends FragmentActivity implements OnMapReadyCallback {

    private GoogleMap mMap;
    private LatLng posicionInicial;
    private boolean posicionOK = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_ubicacion);
        ActivityCompat.requestPermissions(UbicacionActivity.this,new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, 1);

        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED)
        {
            Toast.makeText(UbicacionActivity.this, "No se han definido los permisos necesarios.", Toast.LENGTH_LONG).show();
        } else {
            // Obtain the SupportMapFragment and get notified when the map is ready to be used.
            SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                    .findFragmentById(R.id.map);
            mapFragment.getMapAsync(this);
        }

    }


    /**
     * Manipulates the map once available.
     * This callback is triggered when the map is ready to be used.
     * This is where we can add markers or lines, add listeners or move the camera. In this case,
     * we just add a marker near Sydney, Australia.
     * If Google Play services is not installed on the device, the user will be prompted to install
     * it inside the SupportMapFragment. This method will only be triggered once the user has
     * installed Google Play services and returned to the app.
     */
    @Override
    public void onMapReady(GoogleMap googleMap) {
        mMap = googleMap;
        final OkHttpClient okHttpClient = new OkHttpClient().newBuilder()
                .connectTimeout(60, TimeUnit.SECONDS)
                .readTimeout(60,TimeUnit.SECONDS)
                .writeTimeout(60,TimeUnit.SECONDS)
                .build();
        final Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/ws/")
                .addConverterFactory(GsonConverterFactory.create())
                .client(okHttpClient)
                .build();

        UbicacionService servicioUbicacion = retrofit.create(UbicacionService.class);
        Call<Ubicacion> ubicacionCall = servicioUbicacion.getUbicacion(true);
        ubicacionCall. enqueue(new Callback<Ubicacion>() {
            @Override
            public void onResponse(Call<Ubicacion> call, Response<Ubicacion> response) {
                posicionInicial = new LatLng(response.body().getLatitud(),response.body().getLongitud());


                // LatLng unlam = new LatLng(-34.670345, -58.564357);
                mMap.addMarker(new MarkerOptions().position(posicionInicial).title("Posición actual de la casa").draggable(true));

                CameraPosition camera = new CameraPosition.Builder()
                        .target(posicionInicial)
                        .zoom(17)
                        .build();
                mMap.animateCamera(CameraUpdateFactory.newCameraPosition(camera));
                mMap.setOnMapLongClickListener(new GoogleMap.OnMapLongClickListener() {
                    @Override
                    public void onMapLongClick(final LatLng latLng) {
                        final LatLng nuevaPosicion = latLng;

                        mMap.addMarker(new MarkerOptions().position(nuevaPosicion).title("Nueva ubicacion").draggable(true));
                        CameraPosition camera = new CameraPosition.Builder()
                                .target(nuevaPosicion)
                                .zoom(17)
                                .build();
                        mMap.animateCamera(CameraUpdateFactory.newCameraPosition(camera));

                        AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(UbicacionActivity.this);

                        final TextView popupSolcitudes = new TextView(UbicacionActivity.this);
                        popupSolcitudes.setText("\n¿Definir nueva ubicación?");
                        popupSolcitudes.setTextSize(18);
                        popupSolcitudes.isTextAlignmentResolved();
                        // set prompts.xml to alertdialog builder
                        alertDialogBuilder.setView(popupSolcitudes);

                        // set dialog message
                        alertDialogBuilder.setCancelable(false).setPositiveButton("OK", new DialogInterface.OnClickListener() {
                            public void onClick(DialogInterface dialog, int id) {


                                UbicacionService servicioUbicacion = retrofit.create(UbicacionService.class);
                                Call<Ubicacion> ubicacionCall = servicioUbicacion.setUbicacion(true,nuevaPosicion.latitude,nuevaPosicion.longitude);
                                ubicacionCall.enqueue(new Callback<Ubicacion>() {
                                    @Override
                                    public void onResponse(Call<Ubicacion> call, Response<Ubicacion> response) {
                                       Toast.makeText(UbicacionActivity.this, "Ubicación guardada", Toast.LENGTH_LONG).show();
                                    }

                                    @Override
                                    public void onFailure(Call<Ubicacion> call, Throwable t) {
                                        Toast.makeText(UbicacionActivity.this, Constantes.RESPUESTA_404, Toast.LENGTH_LONG).show();
                                    }
                                });


                            }
                        });
                        alertDialogBuilder.setCancelable(false).setNegativeButton("Cancelar", new DialogInterface.OnClickListener(){
                            public void onClick(DialogInterface dialog, int id) {
                            }
                        });
                        // create alert dialog
                        AlertDialog alertDialog = alertDialogBuilder.create();
                        // show it
                        alertDialog.show();


                    }
                });


            }

            @Override
            public void onFailure(Call<Ubicacion> call, Throwable throwable) {
                Toast.makeText(UbicacionActivity.this, "Error al obtener las coordenadas del servidor", Toast.LENGTH_LONG).show();
                return;
            }
        });



    }
}
