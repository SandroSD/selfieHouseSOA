package com.selfiehouse.selfiehouse.Clases;

public class EstadoComponente {

    private int id, estado;
    private String nombre, fecha;

    public EstadoComponente(int id, String nombre, int estado,  String fecha) {
        this.id = id;
        this.estado = estado;
        this.nombre = nombre;
        this.fecha = fecha;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getEstado() {
        return estado;
    }

    public void setEstado(int estado) {
        this.estado = estado;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getFecha() {
        return fecha;
    }

    public void setFecha(String fecha) {
        this.fecha = fecha;
    }
}
