package com.selfiehouse.selfiehouse.Clases;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageButton;
import android.widget.TextView;

import com.selfiehouse.selfiehouse.Clases.AccesoSolicitud;
import com.selfiehouse.selfiehouse.R;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;

public class SolAccAdapter extends BaseAdapter {
    private List<AccesoSolicitud> listSolicitud;
    private Context contexto;
    private int layout;

    public SolAccAdapter(Context contexto, int layout, List<AccesoSolicitud> solicitudes){
        this.contexto = contexto;
        this.layout = layout;
        this.listSolicitud = solicitudes;

    }
    @Override
    public int getCount() {
        return this.listSolicitud.size();
    }

    @Override
    public Object getItem(int position) {
        return this.listSolicitud.get(position);
    }

    @Override
    public long getItemId(int id) {
        return id;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        //Copio la vista
        View v = convertView;
        SimpleDateFormat formateador = new SimpleDateFormat("dd/MM/yy");

        //Inflo la vista que me llega con el layout personalizado
        LayoutInflater layoutInflater = LayoutInflater.from(this.contexto);
        v = layoutInflater.inflate(R.layout.item_solicitud,null);

        //Me traigo el valor actual dependiendo de la posicion
        AccesoSolicitud list = listSolicitud.get(position);

        //Referencio el elemento a modificar y lo lleno
        TextView tvSol = (TextView) v.findViewById(R.id.tvSolicitud);
        TextView tvFecha = (TextView) v.findViewById(R.id.tvFecha);

        tvSol.setText("Solicitud "+ list.getId());
        tvFecha.setText(list.getFecha());

        //Devuelvo la vista inflada y modificada con nuestros datos
        return v;
    }

}
