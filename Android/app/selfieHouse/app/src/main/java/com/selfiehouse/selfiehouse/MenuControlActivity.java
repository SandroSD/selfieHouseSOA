package com.selfiehouse.selfiehouse;

import android.content.DialogInterface;
import android.content.pm.ActivityInfo;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.Clases.EstadoComponente;
import com.selfiehouse.selfiehouse.Clases.Respuesta;
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
    private MenuItem cantidadDeSolicitudes;
    Switch switchSistema, switchDEBUG, switchBuzzer, switchVentilador, switchTraba;
    ShakeListener mShaker;
    TextView tvTemperatura, tvMovimiento, tvLuz, tvFlama;
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

        /* Deteccion de Shake*/

        mShaker = new ShakeListener(this);
        mShaker.setOnShakeListener(new ShakeListener.OnShakeListener () {
            public void onShake()
            {
                Toast.makeText(MenuControlActivity.this, "*Shake Detectado*" , Toast.LENGTH_SHORT).show();


                ComandoArduino servicioAccion = retrofitC.create( ComandoArduino.class);
                Call<RespuestaSensores> serviciosCall = servicioAccion.infoSensores();
                serviciosCall.enqueue(new Callback<RespuestaSensores>() {
                    @Override
                    public void onResponse(Call<RespuestaSensores> call, Response<RespuestaSensores> response) {
                        //RespuestaSensores rs = response.body();

                        tvTemperatura.setText("Temperatura: "+response.body().getTemperatura()+"°");
                        tvMovimiento.setText("Detección de Movimiento: "+response.body().getMovimiento());
                        tvLuz.setText("Nivel de luz: "+response.body().getLuz());
                        tvFlama.setText("Detección de fuego: "+response.body().getFlama());

                        System.out.println(response.body().getTemperatura());
                        System.out.println(response.body().getMovimiento());
                        System.out.println(response.body().getLuz());
                        System.out.println(response.body().getFlama());


                    }

                    @Override
                    public void onFailure(Call<RespuestaSensores> call, Throwable t) {
                        System.out.println(t.getMessage());
                    }
                });
             }

        });


        /* Cambio los titulos para que no sean editables*/
        EditText mEdit = (EditText) findViewById(R.id.titleEstadoActuadores);
        mEdit.setEnabled(false);
        mEdit = (EditText) findViewById(R.id.titleEstadoAlertas);
        mEdit.setEnabled(false);
        mEdit = (EditText) findViewById(R.id.titleEstadoSistema);
        mEdit.setEnabled(false);

        mTextMessage = (TextView) findViewById(R.id.message);
        final BottomNavigationView[] navigation = {(BottomNavigationView) findViewById(R.id.navigation)};
        navigation[0].setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener);

        /* Seteo los estados iniciales de los botones switch */

        EstadoComponenteService servicioEstadoComponente = retrofit.create(EstadoComponenteService.class);
        Call <List<EstadoComponente>> serviciosEstadosComponentesCall = servicioEstadoComponente.getEstadosComponentes(true);
        serviciosEstadosComponentesCall.enqueue(new Callback<List<EstadoComponente>>() {
            @Override
            public void onResponse(Call<List<EstadoComponente>> call, Response<List<EstadoComponente>> response) {
                List<EstadoComponente> ec = response.body();

                for (int i = 0 ; i < Constantes.CANTIDAD_ESTADOS; i++){
                    switch(ec.get(i).getId()){
                        case Constantes.ID_SELFIEHOUSE:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                System.out.println("ID: SelfieHouse activado");
                                switchSistema.setChecked(true);
                            }
                            break;
                        case Constantes.ID_DEBUG:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                System.out.println("ID: Debug activado");
                                switchDEBUG.setChecked(true);
                            }
                            break;

                        case Constantes.ID_TRABA:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                System.out.println("ID: Traba activado");
                                switchTraba.setChecked(true);
                            }
                            break;
                        case Constantes.ID_BUZZER:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                System.out.println("ID: Buzzer activado");
                                switchBuzzer.setChecked(true);
                            }
                            break;
                        case Constantes.ID_VENTILADOR:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                System.out.println("ID: Ventilador activado");
                                switchVentilador.setChecked(true);
                            }
                            break;

                    }
                }

            }

            @Override
            public void onFailure(Call<List<EstadoComponente>> call, Throwable throwable) {
                Toast.makeText(MenuControlActivity.this, "Error al precargar informacion del servidor", Toast.LENGTH_SHORT).show();
            }
        });



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
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"SelfieHouse: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"SelfieHouse: ACTIVADO", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });

                    /*switchDEBUG.setEnabled(true);
                    switchBuzzer.setEnabled(true);
                    switchTraba.setEnabled(true);
                    switchVentilador.setEnabled(true);*/
                 } else {

                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.desactivarSelfieHouse();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"SelfieHouse: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"SelfieHouse: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });

                    /*switchDEBUG.setChecked(false);
                    switchBuzzer.setChecked(false);
                    switchTraba.setChecked(false);
                    switchVentilador.setChecked(false);*/

                    /*switchDEBUG.setEnabled(false);
                    switchBuzzer.setEnabled(false);
                    switchTraba.setEnabled(false);
                    switchVentilador.setEnabled(false);*/
                }
            }
        });

        /* Listener para switchDEBUG */
        switchDEBUG = (Switch) findViewById(R.id.switchDebug);
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
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Ventilador: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                           // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"Ventilador: ACTIVADO", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });
                } else {
                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.apagarVentilador();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Ventilador: APAGADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"Ventilador: APAGADO", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
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
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Buzzer: ENCENDIDO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"Buzzer: ENCENDIDO", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });


                } else {

                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.apagarBuzzer();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Buzzer: APAGADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"Buzzer: APAGADO", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
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
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Puerta: TRABADA", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"Puerta: TRABADA", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });


                } else {

                    ComandoArduino servicioAccion = retrofitB.create( ComandoArduino.class);
                    Call<Respuesta> serviciosCall = servicioAccion.destrabarPuerta();
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            System.out.println(response.body().getRespuesta());
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Puerta: DESTRABADA", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            // switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,"Puerta: DESTRABADA", Toast.LENGTH_SHORT).show();
                            // Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });

                }
            }
        });


        /* Obtengo la cantidad de solicitudes*/
        AccesoSolicitudService servicioSolicitudAcceso = retrofit.create(AccesoSolicitudService.class);
        Call<List<AccesoSolicitud>> serviciosCall = servicioSolicitudAcceso.getAccesoSolicitud(true);
        serviciosCall.enqueue(new Callback<List<AccesoSolicitud>>() {
            @Override
            public void onResponse(Call<List<AccesoSolicitud>> call, Response<List<AccesoSolicitud>> response) {

                List<AccesoSolicitud> as = response.body();
                //cantidadDeSolicitudes = (TextView) findViewById(R.id.textCantidadDeSolicitudes);
                //cantidadDeSolicitudes.setText("Solicitudes: "+as.size());

                AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(MenuControlActivity.this);

                final TextView popupSolcitudes = new TextView(MenuControlActivity.this);
                popupSolcitudes.setText("\nUsted tiene "+as.size()+" solicitud/es pendiente/s");
                popupSolcitudes.setTextSize(18);
                popupSolcitudes.isTextAlignmentResolved();
                // set prompts.xml to alertdialog builder
                alertDialogBuilder.setView(popupSolcitudes);

                // set dialog message
                alertDialogBuilder.setCancelable(false).setPositiveButton("OK", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int id) {
                    }
                });
                alertDialogBuilder.setCancelable(false).setNegativeButton("Ver después", new DialogInterface.OnClickListener(){
                    public void onClick(DialogInterface dialog, int id) {
                    }
                });
                // create alert dialog
                AlertDialog alertDialog = alertDialogBuilder.create();
                // show it
                alertDialog.show();

                System.out.println("Solicitudes ( "+as.size()+")");
              //  cantidadDeSolicitudes.setTitle("Solicitudes ( "+as.size()+")");
                //cantidadDeSolicitudes.setText("Solicitudes ( "+as.size()+")");
            }

            @Override
            public void onFailure(Call<List<AccesoSolicitud>> call, Throwable throwable) {
                System.out.println("Error: "+throwable.getMessage());
            }
        });
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