<?php

declare(strict_types=1);

namespace TrueIfNotFalse\LumenPrometheusExporter;

use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller;
use Prometheus\RenderTextFormat;

class MetricsController extends Controller
{
    /**
     * @var PrometheusExporter
     */
    protected $prometheusExporter;

    /**
     * @param PrometheusExporter $prometheusExporter
     */
    public function __construct(PrometheusExporter $prometheusExporter)
    {
        $this->prometheusExporter = $prometheusExporter;
    }

    /**
     * The route path is configurable in the prometheus.metrics_route_path config var, or the
     * PROMETHEUS_METRICS_ROUTE_PATH env var.
     *
     * @return Response
     */
    public function getMetrics(): Response
    {
        $metrics  = $this->prometheusExporter->export();
        $renderer = new RenderTextFormat();
        $result   = $renderer->render($metrics);

        return (new Response($result, 200))
            ->header('Content-Type', RenderTextFormat::MIME_TYPE);
    }
}
