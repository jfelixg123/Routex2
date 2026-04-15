<template>
  <div id="superset-container" style="height: 100%; width: 100%;" />
</template>

<style scoped>
#superset-container {
  height: 800px;
}
</style>

<script setup>
import  {  embedDashboard  }  from  "@superset-ui/embedded-sdk" ;

embedDashboard ( {
  id : "abc123" ,  // dado por la interfaz de usuario de incrustación de Superset
  supersetDomain : "https://superset.example.com" ,
  mountPoint : document . getElementById ( "my-superset-container" ) ,  // cualquier elemento html que pueda contener un iframe
  fetchGuestToken : ( )  =>  fetchGuestTokenFromBackend ( ) ,
  dashboardUiConfig : {  // configuración de la interfaz de usuario del panel: hideTitle, hideTab, hideChartControls, filters.visible, filters.expanded (opcional), urlParams (opcional)
      hideTitle : true ,
      filters : {
          expanded : true ,
      } ,
      urlParams : {
          foo : 'value1' ,
          bar : 'value2' ,
          // ...
      }
  } ,
    // atributos adicionales opcionales de la zona de pruebas del iframe
  iframeSandboxExtras : [ 'allow-top-navigation' ,  'allow-popups-to-escape-sandbox' ] ,
  // configuración opcional para aplicar una referrerPolicy particular
  referrerPolicy : "same-origin"
} ) ;
</script>
