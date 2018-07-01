package com.selfiehouse.selfiehouse.Servicios;

import com.selfiehouse.selfiehouse.Clases.Respuesta;
import com.selfiehouse.selfiehouse.Clases.RespuestaActuadores;
import com.selfiehouse.selfiehouse.Clases.RespuestaSensores;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.GET;

public interface ComandoArduino {

    @GET("fanon")
    Call<Respuesta> encenderVentilador();

    @GET("fanoff")
    Call<Respuesta> apagarVentilador();

    @GET("buzzon")
    Call<Respuesta> encenderBuzzer();

    @GET("buzzoff")
    Call<Respuesta> apagarBuzzer();

    @GET("lock")
    Call<Respuesta> trabarPuerta();

    @GET("unlock")
    Call<Respuesta> destrabarPuerta();

    @GET("debugon")
    Call<Respuesta> activarDebug();

    @GET("debugoff")
    Call<Respuesta> desactivarDebug();

    @GET("selfieon")
    Call<Respuesta> activarSelfieHouse();

    @GET("selfieoff")
    Call<Respuesta> desactivarSelfieHouse();

    @GET("infoSensores")
    Call<RespuestaSensores> infoSensores();

    @GET("infoActuadores")
    Call<RespuestaActuadores> infoActuadores();

    @GET("redoff")
    Call<Respuesta> apagarLEDRojo();

    @GET("reset")
    Call<Respuesta> reiniciarArduino();

}
