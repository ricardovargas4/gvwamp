
$(document).ready(function () {
    //$('.datepicker').datepicker({setDefaultDate:false,format:'dd/mm/yyyy'});
    $('.collapsible').collapsible();
    $('.dropdown-trigger').dropdown({hover: false,coverTrigger: false});
    $('.modal').modal();
    $('.modalNotify').modal();
    $('.dropdown-button').dropdown({
        hover: false,
        belowOrigin: true
      }
    );   

    $('.datepicker').datepicker({
      i18n: {
      months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
      monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
      weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabádo'],
      weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
      weekdaysAbbrev: [ 'D', 'S', 'T', 'Q', 'Q', 'S', 'S' ],
      today: 'Hoje',
      labelMonthNext: 'Próximo mês',
      labelMonthPrev: 'Mês anterior',
      labelMonthSelect: 'Selecione um mês',
      labelYearSelect: 'Selecione um ano',
      clear: 'Limpar',
      cancel: 'Cancelar',
      setDefaultDate: true,
      defaultDate: Date.now(),
    },
    format: 'dd/mm/yyyy',
    //format: 'yyyy/mm/dd',
    setDefaultDate: true,
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15, // Creates a dropdown of 15 years to control year,
    //clear: 'Clear',
    done: 'Ok',
    autoClose: true, // Close upon selecting a date,
    //container: undefined, // ex. 'body' will append picker to body
    showClearBtn: true,
     });
    $('.datepicker').datepicker('setDate', new Date());
 });
    
  