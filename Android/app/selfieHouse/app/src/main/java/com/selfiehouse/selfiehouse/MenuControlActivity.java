package com.selfiehouse.selfiehouse;

import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;

import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.Clases.EstadoComponente;
import com.selfiehouse.selfiehouse.Clases.Respuesta;
import com.selfiehouse.selfiehouse.Servicios.AccesoSolicitudService;
import com.selfiehouse.selfiehouse.Clases.Constantes;
import com.selfiehouse.selfiehouse.Servicios.AccionService;
import com.selfiehouse.selfiehouse.Servicios.EstadoComponenteService;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class MenuControlActivity extends AppCompatActivity implements Constantes {

    private TextView mTextMessage;
    Switch switchSistema, switchDEBUG, switchBuzzer, switchVentilador, switchTraba, switchLEDVerde, switchLEDRojo;

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
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_control);
        final Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/ws/")
                .addConverterFactory(GsonConverterFactory.create())
                .build();


        /* Cambio los titulos para que no sean editables*/
        EditText mEdit = (EditText) findViewById(R.id.titleEstadoActuadores);
        mEdit.setEnabled(false);
        mEdit = (EditText) findViewById(R.id.titleEstadoAlertas);
        mEdit.setEnabled(false);
        mEdit = (EditText) findViewById(R.id.titleEstadoSistema);
        mEdit.setEnabled(false);

        mTextMessage = (TextView) findViewById(R.id.message);
        BottomNavigationView navigation = (BottomNavigationView) findViewById(R.id.navigation);
        navigation.setOnNavigationItemSelectedListener(mOnNavigationItemSelectedListener);

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
                                switchSistema.setChecked(true);
                            }
                            break;
                        case Constantes.ID_DEBUG:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                switchDEBUG.setChecked(true);
                            }
                            break;

                        case Constantes.ID_TRABA:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                switchTraba.setChecked(true);
                            }
                            break;
                        case Constantes.ID_BUZZER:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                switchBuzzer.setChecked(true);
                            }
                            break;
                        case Constantes.ID_VENTILADOR:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                switchVentilador.setChecked(true);
                            }
                            break;
                        case Constantes.ID_LED_ROJO:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                switchLEDRojo.setChecked(true);
                            }
                            break;
                        case Constantes.ID_LED_VERDE:
                            if(ec.get(i).getEstado() == Constantes.ACTIVADO){
                                switchLEDVerde.setChecked(true);
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
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.SELFIEHOUSE_ACTIVADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"selfieHouse: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchVentilador.setChecked(false);
                            System.out.println("Entre 3 "+throwable.getMessage());
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });

                    switchDEBUG.setEnabled(true);
                    switchBuzzer.setEnabled(true);
                    switchTraba.setEnabled(true);
                    switchLEDRojo.setEnabled(true);
                    switchLEDVerde.setEnabled(true);
                    switchVentilador.setEnabled(true);
                 } else {
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.SELFIEHOUSE_DESACTIVADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                System.out.println("Entre 1 "+response.body().getRespuesta());
                                Toast.makeText(MenuControlActivity.this,"selfieHouse: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                System.out.println("Entre 1 "+response.body().getRespuesta());
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            System.out.println("Error: "+throwable.getMessage());
                            switchSistema.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();

                        }
                    });

                    switchDEBUG.setChecked(false);
                    switchBuzzer.setChecked(false);
                    switchTraba.setChecked(false);
                    switchLEDRojo.setChecked(false);
                    switchLEDVerde.setChecked(false);
                    switchVentilador.setChecked(false);

                    switchDEBUG.setEnabled(false);
                    switchBuzzer.setEnabled(false);
                    switchTraba.setEnabled(false);
                    switchLEDRojo.setEnabled(false);
                    switchLEDVerde.setEnabled(false);
                    switchVentilador.setEnabled(false);
                }
            }
        });

        /* Listener para switchDEBUG */
        switchDEBUG = (Switch) findViewById(R.id.switchDebug);
        switchDEBUG.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.VENTILADOR_ACTIVADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Ventilador: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });
                } else {
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.DEBUG_DESACTIVADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Modo Debug: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchDEBUG.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
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
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.VENTILADOR_ACTIVADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Ventilador: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });
                } else {
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.VENTILADOR_DESACTIVADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Ventilador: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchVentilador.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
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
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.BUZZER_ACTIVADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Buzzer: ACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchBuzzer.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }


                    });

                } else {

                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.BUZZER_DESACTIVADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"Buzzer: DESACTIVADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchBuzzer.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
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
                    // Nueva peticion HTTP
                    // SI esta OK, que avise mediante toast


                    AccesoSolicitudService servicioSolicitudAcceso = retrofit.create(AccesoSolicitudService.class);
                    Call<List<AccesoSolicitud>> serviciosCall = servicioSolicitudAcceso.getAccesoSolicitud(true);
                    serviciosCall.enqueue(new Callback<List<AccesoSolicitud>>() {
                        @Override
                        public void onResponse(Call<List<AccesoSolicitud>> call, Response<List<AccesoSolicitud>> response) {

                            List<AccesoSolicitud> as = response.body();

                            for(int i=0; i<as.size();i++){
                                System.out.println(as.get(i).getId());
                                System.out.println(as.get(i).getFoto());
                                System.out.println(as.get(i).getFecha());
                            }
                        }

                        @Override
                        public void onFailure(Call<List<AccesoSolicitud>> call, Throwable throwable) {
                            System.out.println("Error: "+throwable.getMessage());
                        }
                    });

                    Toast.makeText(MenuControlActivity.this,"Puerta trabada", Toast.LENGTH_SHORT).show();


                } else {
                    Toast.makeText(MenuControlActivity.this,"Puerta destrabada", Toast.LENGTH_SHORT).show();
                    //textView.setText(switchOff);
                }
            }
        });


        /* Listener para switchLEDRojo */
        switchLEDRojo = (Switch) findViewById(R.id.switchLEDRojo);
        switchLEDRojo.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.LED_ROJO_ENCENDIDO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"LED Rojo: ENCENDIDO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchLEDRojo.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }


                    });

                } else {
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.LED_ROJO_APAGADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"LED Rojo: APAGADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchLEDRojo.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }


                    });

                }
            }
        });



        /* Listener para switchLEDVerde */
        switchLEDVerde = (Switch) findViewById(R.id.switchLEDVerde);
        switchLEDVerde.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton compoundButton, boolean bChecked) {
                if (bChecked) {
                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.LED_VERDE_ENCENDIDO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"LED Verde: ENCENDIDO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchLEDVerde.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }


                    });

                } else {

                    AccionService servicioAccion = retrofit.create(AccionService.class);
                    Call<Respuesta> serviciosCall = servicioAccion.enviarAccion(Constantes.LED_VERDE_APAGADO,Constantes.DISPARADOR_MANUAL);
                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("OK")){
                                Toast.makeText(MenuControlActivity.this,"LED Verde: APAGADO", Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_ERROR_ACCION, Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            switchLEDVerde.setChecked(false);
                            Toast.makeText(MenuControlActivity.this,Constantes.RESPUESTA_404, Toast.LENGTH_SHORT).show();
                        }
                    });

                }
            }
        });
    }
}