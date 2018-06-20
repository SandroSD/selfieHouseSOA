package com.selfiehouse.selfiehouse.Clases;

public class Circulo {

	private Punto centro;
	private double radio;

	public Circulo(Punto centro, double radio) {
		this.centro = centro;
		this.radio = radio;
	}
	
	public boolean intersectaCon(Circulo circulo) {
		//System.out.println(this.toString());
		//System.out.println(circulo.toString());
		return this.centro.distanciaCon(circulo.centro) <= this.radio + circulo.radio;
	}

	@Override
	public String toString() {
		return "Circulo{" +
				"centro=" + centro +
				", radio=" + radio +
				'}';
	}
}