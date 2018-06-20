package com.selfiehouse.selfiehouse.Clases;

public class RespuestaActuadores {
    private String selfiehouse, debug, puerta, buzzer, ventilador, ledrojo, ledverde;

    public RespuestaActuadores(String selfiehouse, String debug, String puerta, String buzzer, String ventilador, String ledrojo, String ledverde) {
        this.selfiehouse = selfiehouse;
        this.debug = debug;
        this.puerta = puerta;
        this.buzzer = buzzer;
        this.ventilador = ventilador;
        this.ledrojo = ledrojo;
        this.ledverde = ledverde;
    }

    public String getSelfiehouse() {
        return selfiehouse;
    }

    public void setSelfiehouse(String selfiehouse) {
        this.selfiehouse = selfiehouse;
    }

    public String getDebug() {
        return debug;
    }

    public void setDebug(String debug) {
        this.debug = debug;
    }

    public String getPuerta() {
        return puerta;
    }

    public void setPuerta(String puerta) {
        this.puerta = puerta;
    }

    public String getBuzzer() {
        return buzzer;
    }

    public void setBuzzer(String buzzer) {
        this.buzzer = buzzer;
    }

    public String getVentilador() {
        return ventilador;
    }

    public void setVentilador(String ventilador) {
        this.ventilador = ventilador;
    }

    public String getLedrojo() {
        return ledrojo;
    }

    public void setLedrojo(String ledrojo) {
        this.ledrojo = ledrojo;
    }

    public String getLedverde() {
        return ledverde;
    }

    public void setLedverde(String ledverde) {
        this.ledverde = ledverde;
    }
}
