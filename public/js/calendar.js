function displayEvents(DOMElementId, events=[]) {
    try {
        var calenderDiv = document.getElementById(DOMElementId);
        var currentMonth = new Date().getMonth();
        var daysOfMonth = new Date(
            new Date().getYear(),
            currentMonth + 1,
            0
        ).getDate();
        var firstDayOffset = new Date(
            new Date().getFullYear(),
            new Date().getMonth(),
            1
        ).getDay();
        for (var i = 0; i <= Math.abs(firstDayOffset - 5); i++) {
            var dayCell = document.createElement("span");
            dayCell.className = "jzdb";
            calenderDiv.appendChild(dayCell);
        }

        for (var i = 1; i <= daysOfMonth; i++) {
            var day = new Date(
                new Date().getFullYear(),
                new Date().getMonth(),
                i
            ).getDay();
            if (day === 4 || day === 5) {
                var dayCell = document.createElement("span");
                dayCell.className = "jzdb";
                calenderDiv.appendChild(dayCell);
            } else {
                var dayCell = document.createElement("span");
                var dayEvents=events.filter(event=>event.day===i);
                if(dayEvents.length){
                    dayCell.className = "circle";
                    dayCell.setAttribute("data-title",dayEvents.map(el=>el.title).join(' et '));
                    dayCell.setAttribute("data-toggle","tooltip");
                    dayCell.setAttribute("data-placement","top");
                    // dayCell.setAttribute("title",dayEvents.map(el=>el.title).join(' et '));

                    dayEvents.forEach(el=>{
    
                    })
                }
                
                dayCell.innerHTML = i;
                calenderDiv.appendChild(dayCell);
            }
        }
    } catch (error) {}
}
//
// var calenderDiv = document.getElementById("up-events-calendar");
// var currentMonth = new Date().getMonth();
// var daysOfMonth = new Date(new Date().getYear(), currentMonth + 1, 0).getDate();
// var firstDayOffset = new Date(
//     new Date().getFullYear(),
//     new Date().getMonth(),
//     1
// ).getDay();
// for (var i = 0; i <= Math.abs(firstDayOffset - 5); i++) {
//     var dayCell = document.createElement("span");
//     dayCell.className = "jzdb";
//     calenderDiv.appendChild(dayCell);
// }

// for (var i = 1; i <= daysOfMonth; i++) {
//     var day = new Date(
//         new Date().getFullYear(),
//         new Date().getMonth(),
//         i
//     ).getDay();
//     if (day === 5 || day === 6) {
//         var dayCell = document.createElement("span");
//         dayCell.className = "jzdb";
//         calenderDiv.appendChild(dayCell);
//     } else {
//         var dayCell = document.createElement("span");
//         if (false) {
//             dayCell.className = "circle";
//             dayCell.setAttribute("data-title", "Pa");
//         }
//         dayCell.innerHTML = i;
//         calenderDiv.appendChild(dayCell);
//     }
// }
