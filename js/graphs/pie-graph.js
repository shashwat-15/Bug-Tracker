const types = ["Bug/Error", "Feature", "Question"];
const typesCount = [];
const availableTypes = [];
Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};
fetch("../../bug-tracker/database/tickets-types-database.php")
.then(res => res.json())
.then((result) => {
    for(let i =0; i<result.length; i++){
        availableTypes.push(result[i][1]);
        if(result[i][1] === types[0]){
            typesCount[0] = parseInt(result[i][0]);
        }
        else if(result[i][1] === types[1]){
            typesCount[1] = parseInt(result[i][0]);
        }
        else if(result[i][1] === types[2]){
            typesCount[2] = parseInt(result[i][0]);
        }

    }
    let f1 = false;
    if(availableTypes.length < types.length){
        for(let i=0; i<types.length; i++){
            f1 = false;
            for(let j = 0; j <availableTypes.length; j++){
                if(availableTypes[j] === types[i]){ 
                    f1 = true;
                }
            }
            if(!f1){
                typesCount[i] = 0;
            }
        }
    }
    
    ticketTypePieChart(types, typesCount);
    //console.log(result);
});

function ticketTypePieChart(label, data){
    var ctx = document.getElementById('pieChart').getContext('2d');

    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: label,
            datasets: [{
                data: [data[0], data[1], data[2]],
                backgroundColor: [
                    'rgb(220, 53, 69)',
                    'rgb(255, 193, 7)',
                    'rgb(40, 167, 69)'
                ],
                hoverBackgroundColor:['rgb(255, 53, 0)','rgb(255, 255, 0)', 'rgb(40,255,69)'],
                borderAlign: 'inner'
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Tickets by Types',
                position: 'top',
                fontColor: '#343a40',
                fontSize: 16
            },
            legend: {
                display: false,
            },
            legendCallback: function(chart) {
                var legend = [];
                for(var i = 0; i<chart.data.datasets[0].data.length; i++){
                    legend.push('<div class="col p-0 ">');
                    legend.push('<p class="mt-2" style="background-color: '+chart.data.datasets[0].backgroundColor[i]+';border-radius: 3px; height: 11px; width: 11px;"></p>')
                    legend.push('</div>');
                    
                    legend.push('<div class="col pl-2 text-secondary">');
                    legend.push('<span class="text-secondary">' + chart.data.labels[i] + '</span>');
                    // legend.push('</div>');
                    // legend.push('<div class="col">');
                    legend.push('<span class="text-secondary pl-1">('+chart.data.datasets[0].data[i]+')</span>');
                    legend.push('</div>');
                }
                return legend.join("");
            }
        }
    });

    let customPieLegend = pieChart.generateLegend();
    let pieLegend = document.getElementById("pieLegend"); 
    pieLegend.innerHTML = customPieLegend;
}
