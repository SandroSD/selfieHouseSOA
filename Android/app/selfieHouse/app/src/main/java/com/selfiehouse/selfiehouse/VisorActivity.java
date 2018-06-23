package com.selfiehouse.selfiehouse;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.webkit.WebView;
import android.widget.ImageView;

import com.selfiehouse.selfiehouse.Clases.Constantes;

public class VisorActivity extends AppCompatActivity {

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_visor);
        WebView webView = new WebView(this);
        setContentView(webView);
        webView.loadUrl("http://" + Constantes.IP_APACHE + ":" + Constantes.PUERTO_APACHE + "/selfieHouse/src/plugins/verPerfil?op=");

    }
}
