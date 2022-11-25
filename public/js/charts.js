function renderChart(containerId,{type,title,data,...others}){
    if(!containerId || !containerId.length) throw new Error("")
    var ctx = document.getElementById(containerId).getContext("2d");
        return new Chart(ctx, {
            type: type || 'bar',
            data: data,
            options: {
                elements: {
                    rectangle: {
                        borderWidth: 2,
                        borderColor: '#c1c1c1',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: title
                    }

                },
                ...others
            }
        });

}