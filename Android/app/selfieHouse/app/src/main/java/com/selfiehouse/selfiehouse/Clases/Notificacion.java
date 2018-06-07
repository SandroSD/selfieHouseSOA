package com.selfiehouse.selfiehouse.Clases;

public class Notificacion {

    private int id, pendiente;
    private String comentario, fecha;

    public Notificacion(int id,  String fecha,  String comentario, int pendiente) {
        this.id = id;
        this.pendiente = pendiente;
        this.comentario = comentario;
        this.fecha = fecha;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getPendiente() {
        return pendiente;
    }

    public void setPendiente(int pendiente) {
        this.pendiente = pendiente;
    }

    public String getComentario() {
        return comentario;
    }

    public void setComentario(String comentario) {
        this.comentario = comentario;
    }

    public String getFecha() {
        return fecha;
    }

    public void setFecha(String fecha) {
        this.fecha = fecha;
    }
}
