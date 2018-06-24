package com.selfiehouse.selfiehouse;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.webkit.WebView;

import com.selfiehouse.selfiehouse.Clases.Constantes;

public class GenerarCodigoActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_generar_codigo);
        WebView webView = new WebView(this);
        setContentView(webView);
        webView.loadUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/src/plugins/generarCodigo");
    }
}
