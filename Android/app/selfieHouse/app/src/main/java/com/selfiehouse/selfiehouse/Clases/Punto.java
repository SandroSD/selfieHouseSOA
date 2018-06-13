package com.selfiehouse.selfiehouse.Clases;

public class Punto {

	private double x;
	private double y;
	
	public Punto(double x, double y) {
		this.x = x;
		this.y = y;
	}

	public double distanciaCon(Punto punto) {
	    return Math.sqrt( Math.pow(this.x - punto.x, 2)
				+ Math.pow(this.y - punto.y, 2));
	}

	@Override
	public String toString() {
		return "Punto{" +
				"lat=" + x +
				", long=" + y +
				'}';
	}
}