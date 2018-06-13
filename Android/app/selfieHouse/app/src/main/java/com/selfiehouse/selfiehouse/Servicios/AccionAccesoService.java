package com.selfiehouse.selfiehouse.Servicios;

import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.Clases.Respuesta;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.GET;
import retrofit2.http.Query;

public interface AccionAccesoService {

    // ..:8080/ws/AndroidReceiverWs?pull_solicitudes=true

    @GET("AndroidReceiverWs")
        // Estoy creando un metodo que formara la URL. El parametro sera pull_solicitudes
        // luego obtiene la respuesta como un JSON que lo metera un una lista de objetos AccesoSolicitud

    Call<String> validarCodigo(@Query("codigo_acceso") int codigo, @Query("tipo_acceso") int tipo);



}
