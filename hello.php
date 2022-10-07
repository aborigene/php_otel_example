<?php
declare(strict_types=1);

require_once('vendor/autoload.php');

   use OpenTelemetry\SDK\Trace\SpanExporter\ConsoleSpanExporter;
   use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
   use OpenTelemetry\SDK\Trace\TracerProvider;
   use OpenTelemetry\Contrib\OtlpHttp\Exporter as OtlpHttpExporter;
   use OpenTelemetry\API\Trace\Propagation\TraceContextPropagator;
   use OpenTelemetry\Context\Context;
   use GuzzleHttp\Client;
   use GuzzleHttp\Psr7\HttpFactory;

#putenv("OTEL_EXPORTER_OTLP_TRACES_ENDPOINT=https://yvt15579.live.dynatrace.com/api/v2/otlp/v1/traces");
#putenv("OTEL_EXPORTER_OTLP_TRACES_HEADERS=Authorization=Api-Token dt0c01.F32EHC5D7N4ZP2RBWGSWVRAK.JYKBQ4D2LFHCUQEL6VIRLP4V5TGS63USQQTE7X4OSX5ZMYWKXALPP4UG2CGYE6IY");

/*$env = new class() {
            use EnvironmentVariablesTrait;
};

$headers = $env->getMapFromEnvironment(Env::OTEL_EXPORTER_OTLP_TRACES_HEADERS);*/
$remote_endpoint=getenv("REMOTE_ENDPOINT");
echo getenv("REMOTE_ENDPOINT");

   echo 'Starting SpanExporter' . PHP_EOL;

   $tracerProvider =  new TracerProvider(
       new SimpleSpanProcessor(
           new OtlpHttpExporter(new Client(), new HttpFactory(), new HttpFactory())
       )
   );
   foreach($_REQUEST as $name => $value){
	   echo "ReqVar: ".$name."- ".$value;
   }
   $parent = TraceContextPropagator::getInstance()->extract($request->getHeaders());

   $tracer = $tracerProvider->getTracer('io.opentelemetry.contrib.php');
   $rootSpan = $tracer->spanBuilder('process request')->startSpan();
//future spans will be parented to the currently active span
   $rootScope = $rootSpan->activate();
   $span1 = $tracer->spanBuilder('curl')->startSpan();
   $span1Scope = $span1->activate();
   

    $curl = curl_init();

    

   // Optional Authentication:
   //
   $ctx = $span1->storeInContext(Context::getCurrent());
   $carrier = [];
   TraceContextPropagator::getInstance()->inject($carrier, null, $ctx);

   foreach ($carrier as $name => $value) {
	   curl_setopt($curl, CURLOPT_HTTPHEADER, array($name.': '.$value));
	   print("Header: ".$name.": ".$value);
   }

    curl_setopt($curl, CURLOPT_URL, $remote_endpoint.":8080/greeting");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

print("HELLO FROM PHP!!!!");
print($result);
$span1Scope->detach();
$span1->end();
 $rootScope->detach();
$rootSpan->end();

?>
