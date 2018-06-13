package com.selfiehouse.selfiehouse.Clases;

public class AccesoSolicitud {
    //@Expose
    private int id;
    private String fecha, foto;

    public AccesoSolicitud(int ID, String FECHA) {
        this.id = ID;
        this.fecha = FECHA;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getFecha() {
        return fecha;
    }

    public void setFecha(String fecha) {
        this.fecha = fecha;
    }
}
