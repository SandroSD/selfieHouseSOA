package com.selfiehouse.selfiehouse.Servicios;

import com.selfiehouse.selfiehouse.Clases.Notificacion;
import com.selfiehouse.selfiehouse.Clases.Ubicacion;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.GET;
import retrofit2.http.Query;

public interface NotificacionService {

    // ..:8080/ws/AndroidReceiverWs?pull_solicitudes=true

    @GET("AndroidReceiverWs")
        // Estoy creando un metodo que formara la URL. El parametro sera pull_solicitudes
    Call <List<Notificacion>> getNotificacion(@Query("pull_notificaciones") boolean valor);
}
