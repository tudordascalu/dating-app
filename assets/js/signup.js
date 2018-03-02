const authService = require('../../services/authService');
// import authService from '../../services/authService';
// console.log(authService);
$( ".signup-form" ).on( "submit", function( event ) {
    event.preventDefault();
    console.log( $( this ).serialize() );
  });