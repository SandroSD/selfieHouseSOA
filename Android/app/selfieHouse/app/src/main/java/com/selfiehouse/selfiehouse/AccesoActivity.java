package com.selfiehouse.selfiehouse;

import android.content.Intent;
import android.support.design.widget.BottomNavigationView;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.selfiehouse.selfiehouse.Clases.Constantes;
import com.selfiehouse.selfiehouse.Clases.Respuesta;
import com.selfiehouse.selfiehouse.Servicios.AccionAccesoService;
import com.selfiehouse.selfiehouse.Servicios.AccionService;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class AccesoActivity extends AppCompatActivity {
    Button button_Acc;
    EditText password ;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_acceso);

        button_Acc = (Button)findViewById(R.id.buttonAcceso);
        //textoPassword = R.id.editTextCodigoAcceso;

        /* Click listener del boton Acceso */
        final Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/ws/")
                .addConverterFactory(GsonConverterFactory.create())
                .build();


        button_Acc.setOnClickListener(new View.OnClickListener() {

            public void onClick(View v){
                //checkearque haya un password
                password = (EditText) findViewById(R.id.editTextCodigoAcceso);
                System.out.println("pass: "+password.getText());

                int passValue = Integer.parseInt(password.getText().toString());

                if(!password.equals("")){
                    AccionAccesoService servicioAccionAcceso = retrofit.create(AccionAccesoService.class);
                    Call<Respuesta> serviciosCall = servicioAccionAcceso.validarCodigo(passValue,Constantes.ACCESO_NORMAL);

                    serviciosCall.enqueue(new Callback<Respuesta>() {
                        @Override
                        public void onResponse(Call<Respuesta> call, Response<Respuesta> response) {
                            if(response.body().getRespuesta().equals("Autorizado")){
                                Toast.makeText(AccesoActivity.this,"Acceso autorizado, puerta destrabada",Toast.LENGTH_SHORT).show();
                            } else if(response.body().getRespuesta().equals("No autorizado")){
                                Toast.makeText(AccesoActivity.this,"El codigo ingresado no tiene autorizacion para acceder",Toast.LENGTH_SHORT).show();
                            } else {
                                Toast.makeText(AccesoActivity.this,"Codigo incorrecto",Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(Call<Respuesta> call, Throwable throwable) {
                            Toast.makeText(AccesoActivity.this,"No autorizado",Toast.LENGTH_SHORT).show();
                        }
                    });
                } else {
                    Toast.makeText(AccesoActivity.this, "El codigo no puede estar vacío", Toast.LENGTH_SHORT).show();

                }

            }

        });
    }
}
