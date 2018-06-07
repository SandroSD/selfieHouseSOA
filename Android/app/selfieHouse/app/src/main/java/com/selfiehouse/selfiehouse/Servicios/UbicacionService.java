package com.selfiehouse.selfiehouse.Servicios;

import com.selfiehouse.selfiehouse.Clases.Ubicacion;

import retrofit2.Call;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Query;

public interface UbicacionService {

    // ..:8080/ws/AndroidReceiverWs?pull_solicitudes=true

    @GET("AndroidReceiverWs")
        // Estoy creando un metodo que formara la URL. El parametro sera pull_solicitudes
    Call<Ubicacion> getUbicacion(@Query("pull_ubicacion") boolean valor);
}
