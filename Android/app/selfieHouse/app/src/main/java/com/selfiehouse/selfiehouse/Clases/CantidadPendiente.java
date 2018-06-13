package com.selfiehouse.selfiehouse.Clases;

public class CantidadPendiente {
    private String tabla;
    private int cantidad;


    public CantidadPendiente(String tabla, int cantidad) {
        this.tabla = tabla;
        this.cantidad = cantidad;
    }

    public String getTabla() {
        return tabla;
    }

    public void setTabla(String tabla) {
        this.tabla = tabla;
    }

    public int getCantidad() {
        return cantidad;
    }

    public void setCantidad(int cantidad) {
        this.cantidad = cantidad;
    }
}
