package com.selfiehouse.project.selfiehouse;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.TextView;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.support.annotation.NonNull;
import android.support.design.widget.BottomNavigationView;
import android.support.v7.app.AppCompatActivity;
import android.view.MenuItem;
import android.widget.TextView;

public class ActividadBotones extends AppCompatActivity{
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }
    public void solicitarFoto(){
        Uri uri = Uri.parse("urlAppFotos");
        Intent intent = new Intent(Intent.ACTION_VIEW, uri);
        startActivity(intent);
    }
    public void ingresarHogar(View v){
        boolean flgControl = false;
        Intent intent = new Intent (v.getContext(), IngresoActivity.class);
        //Mando parametro para diferenciar si debe ingresar por 4 digitos u 8(Ingreso o control)
        intent.putExtra("flgControl",flgControl);
        startActivityForResult(intent, 0);
    }
}
