$(function () {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar-holder').fullCalendar({
        header: {
            left: 'prev, next',
            center: 'title',
            right: 'month, agendaWeek,agendaDay'
        },
        editable: false,
        weekends: true,
        slotDuration: '00:30:00',
        defaultView: 'agendaWeek',
        draggable: false,
        displayEventTime: true,
        minTime: "06:00:00",
        maxTime: "23:59:59",
//        contentHeight: 500,
        lazyFetching: true,
//        timeFormat: {
//            // for agendaWeek and agendaDay
//            agenda: 'h:mmt',    // 5:00 - 6:30
//
//            // for all other views
//            '': 'h:mmt'         // 7p
//        },
        eventSources: [
            {
                url: Routing.generate('fullcalendar_loader'),
                type: 'POST',
                // A way to add custom filters to your event listeners
                data: {
                },
                error: function() {
                   //alert('There was an error while fetching Google Calendar!');
                }
            }
        ],
        eventClick: function(event) {//Au click sur un evenement
            if (event.url) {}
        },
        eventRender: function(event, element)//Une fois l'évènement affiché, on va rajouté la description
        { 
            if(event.lieu !==null){var  eventLieu = event.lieu;}else{ var  eventLieu = '';}
            if(event.description !==null){var  eventDescription = event.description;}else{ var  eventDescription = '';}
            if(event.annule ===null || event.annule ===false){var  eventStatut = maintenu;}else{ var  eventStatut = annule;}
            if(event.invite ===1){var  eventRole = je_suis_invite;}else{ var  eventRole = '';}
            element.find('.fc-title').append("<br/>" + eventDescription); 
            element.popover({
                title: event.title,
                placement: function(pop,ele){
                            if($(ele).parent().is('td:last-child')){
                            return 'left'
                            }else{
                            return 'top'
                            }
                        },
                trigger: 'hover',
                html:true,
                content: "@"+ event.auteur + "<br /><b>" + debut + "</b>: " + moment(event.start).format('DD-MM-YYYY H:mm') + "<br /><b>" + lieu + "</b>: " + eventLieu + "<br/><b>"+ statut + "</b>: " + eventStatut + "<br/><b>"+ description + "</b>: " + eventDescription +  "<br/>" + eventRole,
                container: 'body'
            });
        },
        dayClick: function (date, allDay, jsEvent, view) {  
            var dateDebut = moment(date).format('DD-MM-YYYY H:mm:ss');
//
//            alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
//
//            alert('Current view: ' + view.name);
            $("#new-event").trigger("click");
            $(document).ajaxComplete(function(){
                $("#evenement_date_debut").val(dateDebut);
            });
        }
        
        
    });
});
