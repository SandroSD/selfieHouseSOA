package com.selfiehouse.selfiehouse.Servicios;
import com.selfiehouse.selfiehouse.Clases.Respuesta;
import retrofit2.Call;
import retrofit2.http.POST;
import retrofit2.http.Query;

public interface AccionService {

    @POST("AndroidReceiverWs")
        // Estoy creando un metodo que formara la URL. El parametro sera pull_solicitudes
        // luego obtiene la respuesta como un JSON que lo metera un una lista de objetos AccesoSolicitud

    Call<Respuesta> enviarAccion(@Query("accion") int accion, @Query("disparador") int disparador);
}