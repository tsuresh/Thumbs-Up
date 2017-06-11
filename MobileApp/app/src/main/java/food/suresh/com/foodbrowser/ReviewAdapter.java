package food.suresh.com.foodbrowser;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.Collections;
import java.util.List;

import food.suresh.com.foodbrowser.reviews.ReviewData;
import me.zhanghai.android.materialratingbar.MaterialRatingBar;


/**
 * Created by Suresh on 6/8/2017.
 */

public class ReviewAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder>  {

    private Context context;
    private LayoutInflater inflater;
    List<ReviewData> data= Collections.emptyList();
    ReviewData current;
    int currentPos=0;

    public ReviewAdapter(Context context, List<ReviewData> data){
        this.context=context;
        inflater= LayoutInflater.from(context);
        this.data=data;
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view=inflater.inflate(R.layout.list_item, parent, false);
        MyHolder holder=new MyHolder(view);
        return holder;
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        MyHolder myHolder= (MyHolder) holder;
        current=data.get(position);
        myHolder.name.setText(current.uname);
        myHolder.description.setText(current.description);

        float avgrating = (Float.parseFloat(current.price) + Float.parseFloat(current.quality) + Float.parseFloat(current.taste)) / 3;

        myHolder.mbar.setRating(avgrating);
    }

    @Override
    public int getItemCount() {
        return data.size();
    }

    class MyHolder extends RecyclerView.ViewHolder{

        TextView name;
        TextView description;
        MaterialRatingBar mbar;

        // create constructor to get widget reference
        public MyHolder(View itemView) {
            super(itemView);
            name= (TextView) itemView.findViewById(R.id.uname);
            description= (TextView) itemView.findViewById(R.id.review);
            mbar= (MaterialRatingBar) itemView.findViewById(R.id.eachrate);
        }

    }
}
