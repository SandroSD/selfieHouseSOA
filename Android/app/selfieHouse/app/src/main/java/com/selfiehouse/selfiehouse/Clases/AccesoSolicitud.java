package com.selfiehouse.selfiehouse.Clases;

public class AccesoSolicitud {
    //@Expose
    private int id;
    private String fecha, foto;

    public AccesoSolicitud(int ID, String FECHA, String FOTO) {
        this.id = ID;
        this.fecha = FECHA;
        this.foto = FOTO;
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

    public String getFoto() {
        return foto;
    }

    public void setFoto(String foto) {
        this.foto = foto;
    }
}
