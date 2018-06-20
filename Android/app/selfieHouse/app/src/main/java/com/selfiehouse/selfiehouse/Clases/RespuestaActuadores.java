package com.selfiehouse.selfiehouse.Clases;

public class RespuestaActuadores {
    private String puerta, buzzer, ventilador, ledrojo, ledverde;

    public RespuestaActuadores(String puerta, String buzzer, String ventilador, String ledrojo, String ledverde) {
        this.puerta = puerta;
        this.buzzer = buzzer;
        this.ventilador = ventilador;
        this.ledrojo = ledrojo;
        this.ledverde = ledverde;
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
