<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* dashboard/index.twig */
class __TwigTemplate_c39af01c57bc6838d09846a970a93e87 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layouts/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->load("layouts/base.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 4
        yield "<div class=\"dashboard-container\">
    ";
        // line 5
        yield from $this->load("components/header.twig", 5)->unwrap()->yield($context);
        // line 6
        yield "
    <div class=\"container\">
        ";
        // line 8
        if ((($tmp = ($context["success"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 9
            yield "            <div class=\"alert alert-success\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["success"] ?? null), "html", null, true);
            yield "</div>
        ";
        }
        // line 11
        yield "        
        ";
        // line 12
        if ((($tmp = ($context["error"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 13
            yield "            <div class=\"alert alert-error\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["error"] ?? null), "html", null, true);
            yield "</div>
        ";
        }
        // line 15
        yield "
        <div class=\"dashboard-header\">
            <h1>Welcome back, ";
        // line 17
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "name", [], "any", false, false, false, 17), "html", null, true);
        yield "!</h1>
            <p>Here's your support dashboard overview</p>
        </div>

        <!-- Stats Cards -->
        <div class=\"stats-grid\">
            <div class=\"stat-card\">
                <div class=\"stat-icon\" style=\"background: #dbeafe;\">
                    <span>📊</span>
                </div>
                <div class=\"stat-content\">
                    <h3>";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["stats"] ?? null), "total", [], "any", false, false, false, 28), "html", null, true);
        yield "</h3>
                    <p>Total Tickets</p>
                </div>
            </div>
            
            <div class=\"stat-card\">
                <div class=\"stat-icon\" style=\"background: #d1fae5;\">
                    <span>🔓</span>
                </div>
                <div class=\"stat-content\">
                    <h3>";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["stats"] ?? null), "open", [], "any", false, false, false, 38), "html", null, true);
        yield "</h3>
                    <p>Open Tickets</p>
                </div>
            </div>
            
            <div class=\"stat-card\">
                <div class=\"stat-icon\" style=\"background: #f3f4f6;\">
                    <span>✅</span>
                </div>
                <div class=\"stat-content\">
                    <h3>";
        // line 48
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["stats"] ?? null), "resolved", [], "any", false, false, false, 48), "html", null, true);
        yield "</h3>
                    <p>Resolved Tickets</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class=\"actions-section\">
            <h2>Quick Actions</h2>
            <div class=\"action-buttons\">
                <a href=\"/tickets\" class=\"action-card\">
                    <div class=\"action-icon\">🎫</div>
                    <h3>Manage Tickets</h3>
                    <p>View, create, and manage all support tickets</p>
                </a>
                
                <a href=\"/tickets\" class=\"action-card\" onclick=\"showCreateForm(); return false;\">
                    <div class=\"action-icon\">➕</div>
                    <h3>Create Ticket</h3>
                    <p>Create a new support ticket</p>
                </a>
            </div>
        </div>
    </div>

    ";
        // line 73
        yield from $this->load("components/footer.twig", 73)->unwrap()->yield($context);
        // line 74
        yield "</div>

<style>
.dashboard-container {
    min-height: 100vh;
    background: #f8fafc;
    width: 100%;
}

.dashboard-header {
    margin-bottom: 3rem;
    text-align: center;
}

.dashboard-header h1 {
    font-size: 2.5rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.dashboard-header p {
    color: #6b7280;
    font-size: 1.125rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-content h3 {
    font-size: 2rem;
    color: #1f2937;
    margin: 0;
}

.stat-content p {
    color: #6b7280;
    margin: 0;
}

.actions-section {
    background: white;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}

.actions-section h2 {
    color: #1f2937;
    margin-bottom: 1.5rem;
    text-align: center;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.action-card {
    background: #f8fafc;
    padding: 2rem;
    border-radius: 0.75rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
    border: 2px solid transparent;
    text-align: center;
}

.action-card:hover {
    background: white;
    border-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.action-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.action-card h3 {
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.action-card p {
    color: #6b7280;
    margin: 0;
}

@media (max-width: 768px) {
    .dashboard-header h1 {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showCreateForm() {
    // Redirect to tickets page which will handle the form display
    window.location.href = '/tickets';
}
</script>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "dashboard/index.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  160 => 74,  158 => 73,  130 => 48,  117 => 38,  104 => 28,  90 => 17,  86 => 15,  80 => 13,  78 => 12,  75 => 11,  69 => 9,  67 => 8,  63 => 6,  61 => 5,  58 => 4,  51 => 3,  40 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "dashboard/index.twig", "C:\\Users\\hp\\Desktop\\ticket-management-app\\twig-app\\templates\\dashboard\\index.twig");
    }
}
