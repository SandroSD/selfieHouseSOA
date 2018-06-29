package com.selfiehouse.selfiehouse;

import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.graphics.Color;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import android.os.VibrationEffect;
import android.os.Vibrator;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.view.View;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.Clases.EstadoComponente;
import com.selfiehouse.selfiehouse.NotificacionActivity;
import com.selfiehouse.selfiehouse.Clases.Respuesta;
import com.selfiehouse.selfiehouse.Clases.RespuestaActuadores;
import com.selfiehouse.selfiehouse.Clases.RespuestaSensores;
import com.selfiehouse.selfiehouse.Clases.ShakeListener;
import com.selfiehouse.selfiehouse.Servicios.AccesoSolicitudService;
import com.selfiehouse.selfiehouse.Clases.Constantes;
import com.selfiehouse.selfiehouse.Servicios.ComandoArduino;
import com.selfiehouse.selfiehouse.Servicios.EstadoComponenteService;

import org.w3c.dom.Text;

import java.util.List;
import java.util.concurrent.TimeUnit;

import okhttp3.OkHttpClient;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class MenuControlActivity extends AppCompatActivity implements Constantes {

    private TextView mTextMessage;
    private int cantidadProximidad = 0, cantidadSolitudesAccesoInicial = 0;
    Switch switchSistema, switchBuzzer, switchVentilador, switchTraba;
    ShakeListener mShaker;
    ImageButton btn_Notif, btn_SolAcc, btn_Ubicacion, btn_GenCod;
    TextView tvTemperatura, tvMovimiento, tvLuz, tvFlama, tvSolicitudesAcceso;

    /* Variables para peticiones HTTP*/
    final Retrofit retrofit = new Retrofit.Builder()
            .baseUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/ws/")
            .addConverterFactory(GsonConverterFactory.create())
            .build();

    // Para setear Timeout
    OkHttpClient okHttpClient = new OkHttpClient().newBuilder()
            .connectTimeout(60, TimeUnit.SECONDS)
            .readTimeout(60,TimeUnit.SECONDS)
            .writeTimeout(60,TimeUnit.SECONDS)
            .build();

    final Retrofit retrofitB = new Retrofit.Builder()
            .baseUrl("http://" + Constantes.IP_ARDUINO + "/")
            .addConverterFactory(GsonConverterFactory.create())
            .build();

    Gson gson = new GsonBuilder()
            .setLenient()
            .create();

    final Retrofit retrofitC = new Retrofit.Builder()
            .baseUrl("http://" + Constantes.IP_ARDUINO + "/")
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create(gson))
            .build();


    private BottomNavigationView.OnNavigationItemSelectedListener mOnNavigationItemSelectedListener
            = new BottomNavigationView.OnNavigationItemSelectedListener() {

        @Override
        public boolean onNavigationItemSelected(@NonNull MenuItem item) {
            switch (item.getItemId()) {
                case R.id.navigation_home:
                    mTextMessage.setText(R.string.title_home);
                    return true;
                case R.id.navigation_dashboard:
                    mTextMessage.setText(R.string.title_dashboard);
                    return true;
                case R.id.navigation_notifications:
                    mTextMessage.setText(R.string.title_notifications);
                    return true;
            }
            return false;
        }
    };



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        //getActionBar().setIcon(R.drawable.my_icon);
        setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);      // No permite que la activity se adapte a la rotacion de pantalla
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_control);

        tvTemperatura = (TextView) findViewById(R.id.textViewTemperatura);
        tvMovimiento = (TextView) findViewById(R.id.textViewMovimiento);
        tvLuz = (TextView) findViewById(R.id.textViewLuz);
        tvFlama = (TextView) findViewById(R.id.textViewFuego);
        tvSolicitudesAcceso = (TextView) findViewById(R.id.tvSolAcc);

        /* Cambio los titulos para que no sean editables */

        EditText mEdit = (EditText) findViewById(R.id.titleEstadoActuadores);
        mEdit.setEnabled(false);
        mEdit = (EditText) findViewById(R.id.titleEstadoAlertas);
        mEdit.setEnabled(false);
        mEdit = (EditText) findViewById(R.id.titleEstadoSistema);
        mEdit.setEnabled(false);

        mTextMessage = (TextView) findViewById(R.id.message);
        /* Botones Notificaciones y Solicitudes */

        btn_Notif = (ImageButton) findViewById(R.id.btnNotif);
        btn_SolAcc = (ImageButton) findViewById(R.id.btnSolAcceso);
        btn_Ubicacion = (ImageButton) findViewById(R.id.btnUbicacion);
        btn_GenCod = (ImageButton) findViewById(R.id.btnCodigo);

        btn_Notif.setOnClickListener(new View.OnClickListener() {

            public void onClick(View v) {
                Intent intento = new Intent (MenuControlActivity.this, NotificacionActivity.class);
                startActivity(intento);
            }
        });

        btn_SolAcc.setOnClickListener(new View.OnClickListener() {

            public void onClick(View v){
                // Estoy en MainActivity.this y voy hacia MainActivity.class
                Intent solicitarAccesoIntent = new Intent (MenuControlActivity.this, SolAccesoActivity.class);
                startActivity(solicitarAccesoIntent);
            }

        });

        btn_Ubicacion.setOnClickListener(new View.OnClickListener() {

            public void onClick(View v) {
                Intent intento = new Intent (MenuControlActivity.this, UbicacionActivity.class);
                startActivity(intento);
            }
        });
        btn_GenCod.setOnClickListener(new View.OnClickListener() {

            public void onClick(View v) {
                Intent intento = new Intent (MenuControlActivity.this, GenerarCodigoActivity.class);
                startActivity(intento);
            }
        });




        /* Deteccion de Shake */

        mShaker = new ShakeListener(this);
        mShaker.setOnShakeListener(new ShakeListener.OnShakeListener () {
            public void onShake()
            {
                ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                Call<Respuesta> serviciosCall = servicioAccion.apagarLEDRojo();
                serviciosCall.enqueue(new Callback<Respuesta>() {
                    @Override
                    public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                        if(response.body().getRespuesta().equals("OK")){
                            // Toast.makeText(MenuControlActivity.this,"SelfieHouse: ACTIVADO", Toast.LENGTH_SHORT).show();
                        } else {
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<Respuesta> call, Throwable throwable) {
                        //Toast.makeText(MenuControlActivity.this,"SelfieHouse: ACTIVADO", Toast.LENGTH_SHORT).show();
                    }
                });
            }

        });


        /* Deteccion de proximidad  */
        SensorManager sensorManager = (SensorManager) getSystemService(SENSOR_SERVICE);
        final Sensor proximitySensor = sensorManager.getDefaultSensor(Sensor.TYPE_PROXIMITY);
        if(proximitySensor == null) {
            Toast.makeText(MenuControlActivity.this, "Sensor de proximidad no disponible", Toast.LENGTH_SHORT).show();
            finish(); // Close app
        } else {
            // Create listener
            SensorEventListener proximitySensorListener = new SensorEventListener() {
                @Override
                public void onSensorChanged(SensorEvent sensorEvent) {
                    //Toast.makeText(MenuControlActivity.this, "Se detecta un cambio de proximidad", Toast.LENGTH_SHORT).show();
                    if(sensorEvent.values[0] < proximitySensor.getMaximumRange()) {
                        cantidadProximidad++;
                        if(cantidadProximidad == 5) {
                            Toast.makeText(MenuControlActivity.this, "Modo Debug: ACTIVADO", Toast.LENGTH_SHORT).show();
                            cantidadProximidad = 0;
                            // Detected something nearby
                            final AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(MenuControlActivity.this);

                            final TextView popupProximidad = new TextView(MenuControlActivity.this);
                            popupProximidad.setText("\nEsta es una versión de prueba. Si le gustó puede realizar una donación");
                            popupProximidad.setTextSize(18);
                            popupProximidad.isTextAlignmentResolved();
                            // set prompts.xml to alertdialog builder
                            alertDialogBuilder.setView(popupProximidad);

                            // set dialog message
                            alertDialogBuilder.setCancelable(false).setPositiveButton("Donar", new DialogInterface.OnClickListener() {
                                public void onClick(DialogInterface dialog, int id) {
                                    final TextView popupDonar = new TextView(MenuControlActivity.this);
                                    popupDonar.setText("\nMentira, no hay versión full ;D");
                                    popupDonar.setTextSize(18);
                                    popupDonar.isTextAlignmentResolved();
                                    // set prompts.xml to alertdialog builder
                                    alertDialogBuilder.setView(popupDonar);
                                    AlertDialog alertDialog = alertDialogBuilder.create();
                                    // show it
                                    alertDialog.show();
                                }
                            });
                            alertDialogBuilder.setCancelable(false).setNegativeButton("En otro momento", new DialogInterface.OnClickListener(){
                                public void onClick(DialogInterface dialog, int id) {
                                }
                            });
                            // create alert dialog
                            AlertDialog alertDialog = alertDialogBuilder.create();
                            // show it
                            alertDialog.show();

                        } else {
                            Toast.makeText(MenuControlActivity.this, "Acerquece "+(3-cantidadProximidad)+" veces más para activar el modo DEBUG", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        // Nothing is nearby
                    //    getWindow().getDecorView().setBackgroundColor(Color.WHITE);
                    }
                }

                @Override
                public void onAccuracyChanged(Sensor sensor, int i) {
                   // Toast.makeText(MenuControlActivity.this, "Se detecta un cambio precision", Toast.LENGTH_SHORT).show();
                }


            };



        // Register it, specifying the polling interval in
        // microseconds
            sensorManager.registerListener(proximitySensorListener,
                    proximitySensor, 2 * 1000 * 1000);

        }





        /* Listener para switchSistema */

        switchSistema = (Switch) findViewById(R.id.switchSistema);
        switchSistema.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.activarSelfieHouse();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                               // Toast.makeText(MenuControlActivity.this,"SelfieHouse: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            //Toast.makeText(MenuControlActivity.this,"SelfieHouse: ACTIVADO", Toast.LENGTH_SHORT).show();
                        }
                    });


                 } else {

                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.desactivarSelfieHouse();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                           if(response.body().getRespuesta().equals("OK")){
                            //    Toast.makeText(MenuControlActivity.this,"SelfieHouse: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            //Toast.makeText(MenuControlActivity.this,"SelfieHouse: DESACTIVADO", Toast.LENGTH_SHORT).show();
                        }
                    });

                }
            }
        });

        /* Listener para switchDEBUG */
        /*switchDEBUG = (Switch) findViewById(R.id.switchDebug);
        switchDEBUG.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.activarDebug();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Modo Debug: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"Modo Debug: ACTIVADO", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });

                } else {
                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.desactivarDebug();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Modo Debug: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"Modo Debug: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });

                }
            }
        });
        */

        /* Listener para switchVentilador */
        switchVentilador = (Switch) findViewById(R.id.switchVentilador);
        switchVentilador.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.encenderVentilador();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                            //    Toast.makeText(MenuControlActivity.this,"Ventilador: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                        //   Toast.makeText(MenuControlActivity.this,"Ventilador: ACTIVADO", Toast.LENGTH_SHORT).show();
                        }
                    });
                } else {
                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.apagarVentilador();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                           //     Toast.makeText(MenuControlActivity.this,"Ventilador: APAGADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                        //   Toast.makeText(MenuControlActivity.this,"Ventilador: APAGADO", Toast.LENGTH_SHORT).show();
                        }
                    });
                }
            }
        });

        /* Listener para switchBuzzer */
        switchBuzzer = (Switch) findViewById(R.id.switchBuzzer);
        switchBuzzer.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.encenderBuzzer();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {

                            if(response.body().getRespuesta().equals("OK")){
                            //    Toast.makeText(MenuControlActivity.this,"Buzzer: ENCENDIDO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                           // Toast.makeText(MenuControlActivity.this,"Buzzer: ENCENDIDO", Toast.LENGTH_SHORT).show();
                         }
                    });


                } else {

                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.apagarBuzzer();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                            //    Toast.makeText(MenuControlActivity.this,"Buzzer: APAGADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                           // Toast.makeText(MenuControlActivity.this,"Buzzer: APAGADO", Toast.LENGTH_SHORT).show();
                        }
                    });

                }
            }
        });

        /* Listener para switchTraba */
        switchTraba = (Switch) findViewById(R.id.switchTraba);
        switchTraba.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.trabarPuerta();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                //   Toast.makeText(MenuControlActivity.this,"Puerta: TRABADA", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                   //         Toast.makeText(MenuControlActivity.this,"Puerta: TRABADA", Toast.LENGTH_SHORT).show();
                        }
                    });


                } else {

                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.destrabarPuerta();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
//                                Toast.makeText(MenuControlActivity.this,"Puerta: DESTRABADA", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
//                            Toast.makeText(MenuControlActivity.this,"Puerta: DESTRABADA", Toast.LENGTH_SHORT).show();
                        }
                    });

                }
            }
        });




        actualizarSolicitudesAcceso();
        actualizarDatosActuadores();
        actualizarDatosSensores();


    }

    private void actualizarDatosActuadores() {
        Toast.makeText(MenuControlActivity.this, "Obteniendo estados de la casa... espere", Toast.LENGTH_LONG).show();
        new Thread(new Runnable() {
            private final Handler handler = new Handler() ;
            @Override
            public void run() {
                /* Seteo los estados iniciales de los botones switch */

                ComandoArduino servicioAccion = retrofitC.create( ComandoArduino.class);
                Call<RespuestaActuadores> serviciosCalls = servicioAccion.infoActuadores();

                serviciosCalls.enqueue(new Callback<RespuestaActuadores>() {

                    @Override
                    public void onResponse(Call<RespuestaActuadores> call, Response<RespuestaActuadores> response) {
                        if(response.body().getSelfiehouse().equals("Activado")){
                            switchSistema.setChecked(true);
                        } else {
                            switchSistema.setChecked(false);
                        }

                        if(response.body().getPuerta().equals("Trabada")){
                            switchTraba.setChecked(true);
                        } else {
                            switchTraba.setChecked(false);
                        }

                        if(response.body().getBuzzer().equals("Encendido")){
                            switchBuzzer.setChecked(true);
                        }  else {
                            switchBuzzer.setChecked(false);
                        }

                        if(response.body().getVentilador().equals("Encendido")){
                            switchVentilador.setChecked(true);
                        } else {
                            switchVentilador.setChecked(false);
                        }
                   }

                    @Override
                    public void onFailure(Call<RespuestaActuadores> call, Throwable t) {

                    }
                });
                handler.postDelayed(this, 5000);
            }
        }).start();
    }

    private void actualizarDatosSensores() {
        Toast.makeText(MenuControlActivity.this,"Actualizando datos de sensores", Toast.LENGTH_SHORT).show();
        new Thread(new Runnable() {
            private final Handler handler = new Handler() ;

            @Override
            public void run() {
                ComandoArduino servicioAccion = retrofitC.create(ComandoArduino.class);
                Call<RespuestaSensores> serviciosCall = servicioAccion.infoSensores();
                serviciosCall.enqueue(new Callback<RespuestaSensores>() {
                    @Override
                    public void onResponse(Call<RespuestaSensores> call, Response<RespuestaSensores> response) {
                        //RespuestaSensores rs = response.body();
                        if(response.body().getTemperatura().equals("NaN")){

                        } else {
                            tvTemperatura.setText("Temperatura: "+response.body().getTemperatura()+"°");
                        }

                        tvMovimiento.setText("Detección de Movimiento: "+response.body().getMovimiento());
                        tvLuz.setText("Nivel de luz: "+response.body().getLuz());
                        tvFlama.setText("Detección de fuego: "+response.body().getFlama());
                    }

                    @Override
                    public void onFailure(Call<RespuestaSensores> call, Throwable t) {
                        System.out.println(t.getMessage());
                    }
                });
                handler.postDelayed(this, 5000);
            }
        }).start();
    }

    private void actualizarSolicitudesAcceso() {

        //Toast.makeText(MenuControlActivity.this,"Actualizando datos de sensores", Toast.LENGTH_SHORT).show();
        new Thread(new Runnable() {
            private final Handler handler = new Handler() ;
            private boolean primeraSolicitud = true;
            @Override
            public void run() {
                System.out.println("Consultando solicitudes...");
                AccesoSolicitudService servicioSolicitudAcceso = retrofit.create(AccesoSolicitudService.class);
                Call<List<AccesoSolicitud>> serviciosCall = servicioSolicitudAcceso.getAccesoSolicitud(true);
                serviciosCall.enqueue(new Callback<List<AccesoSolicitud>>() {
                    @Override
                    public void onResponse(Call<List<AccesoSolicitud>> call, Response<List<AccesoSolicitud>> response) {
                        // La primera vez guardo el valor inicial
                        if(primeraSolicitud){
                            cantidadSolitudesAccesoInicial = response.body().size();
                            primeraSolicitud = false;
                        } else {
                            if(response.body().size() != cantidadSolitudesAccesoInicial) {
                                cantidadSolitudesAccesoInicial = response.body().size();
                                AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(MenuControlActivity.this);
                                Vibrator v = (Vibrator) getSystemService(Context.VIBRATOR_SERVICE);
                                v.vibrate(1000);        // No funca :(
                                final TextView popupSolcitudes = new TextView(MenuControlActivity.this);
                                popupSolcitudes.setText("\nUsted tiene una nueva solicitud de acceso!");
                                popupSolcitudes.setTextSize(18);
                                popupSolcitudes.isTextAlignmentResolved();
                                // set prompts.xml to alertdialog builder
                                alertDialogBuilder.setView(popupSolcitudes);
                                alertDialogBuilder.setCancelable(false).setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                    public void onClick(DialogInterface dialog, int id) {
                                    }
                                });
                                AlertDialog alertDialog = alertDialogBuilder.create();
                                tvSolicitudesAcceso.setText("Solicitudes de Acceso ("+response.body().size()+")");

                                alertDialog.show();
                            }

                        }

                    }

                    @Override
                    public void onFailure(Call<List<AccesoSolicitud>> call, Throwable throwable) {
                        System.out.println("Error: "+throwable.getMessage());
                    }
                });
                handler.postDelayed(this, 10000);       // 10 segundos
            }
        }).start();
    }

    @Override
    protected void onResume(){
        super.onResume();
        mShaker.resume();
    }


    @Override
    protected void onPause() {
        super.onPause();
        mShaker.pause();

    }



}