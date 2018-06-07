package com.selfiehouse.selfiehouse.Clases;

public class AccesoCodigo {
    private int numero, permiso, estado;

    public AccesoCodigo(int numero, int permiso, int estado) {
        this.numero = numero;
        this.permiso = permiso;
        this.estado = estado;
    }

    public int getNumero() {
        return numero;
    }

    public void setNumero(int numero) {
        this.numero = numero;
    }

    public int getPermiso() {
        return permiso;
    }

    public void setPermiso(int permiso) {
        this.permiso = permiso;
    }

    public int getEstado() {
        return estado;
    }

    public void setEstado(int estado) {
        this.estado = estado;
    }
}
