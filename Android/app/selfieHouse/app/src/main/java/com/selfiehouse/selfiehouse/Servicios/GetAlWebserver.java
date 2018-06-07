package com.selfiehouse.selfiehouse.Servicios;

import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.GET;
import retrofit2.http.Query;

public interface GetAlWebserver {

    @GET("AndroidReceiverWs")
        // Estoy creando un metodo que formara la URL. El parametro sera pull_solicitudes
        // luego obtiene la respuesta como un JSON que lo metera un una lista de objetos AccesoSolicitud

    Call<List<AccesoSolicitud>> getAccesoSolicitud(@Query("pull_solicitudes") boolean valor);
}
