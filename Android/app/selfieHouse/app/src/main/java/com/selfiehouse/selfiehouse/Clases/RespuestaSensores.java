package com.selfiehouse.selfiehouse.Clases;

public class RespuestaSensores {
   private String temperatura, movimiento, luz, flama;

    public RespuestaSensores(String temperatura, String movimiento, String luz, String flama) {
        this.temperatura = temperatura;
        this.movimiento = movimiento;
        this.luz = luz;
        this.flama = flama;
    }

    public String getTemperatura() {
        return temperatura;
    }

    public void setTemperatura(String temperatura) {
        this.temperatura = temperatura;
    }

    public String getMovimiento() {
        return movimiento;
    }

    public void setMovimiento(String movimiento) {
        this.movimiento = movimiento;
    }

    public String getLuz() {
        return luz;
    }

    public void setLuz(String luz) {
        this.luz = luz;
    }

    public String getFlama() {
        return flama;
    }

    public void setFlama(String flama) {
        this.flama = flama;
    }
}