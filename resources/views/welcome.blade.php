<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Case Técnico — Desenvolvedor Back-end (Laravel)</title>
<style>
:root{
  --bg:#0f1724;
  --card:#0b1220;
  --muted:#9aa6b2;
  --accent:#6ee7b7;
  --accent-2:#60a5fa;
  --glass: rgba(255,255,255,0.03);
  --border: rgba(255,255,255,0.04);
  --maxw:1100px;
  --radius:12px;
  --mono:ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono", "Courier New", monospace;
  --sans: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}
*{box-sizing:border-box}
html,body{height:100%}
body{
  margin:0;
  font-family:var(--sans);
  background:
    radial-gradient(1200px 600px at 10% 10%, rgba(96,165,250,0.06), transparent 8%),
    radial-gradient(900px 450px at 90% 90%, rgba(110,231,183,0.03), transparent 8%),
    var(--bg);
  color: #e6eef6;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  padding:48px 20px;
  display:flex;
  align-items:flex-start;
  justify-content:center;
  gap:24px;
}
.container{
  width:100%;
  max-width:var(--maxw);
}
.header{
  display:flex;
  align-items:center;
  gap:18px;
  margin-bottom:18px;
}
.logo{
  width:74px;
  height:74px;
  border-radius:14px;
  background:linear-gradient(135deg,var(--accent),var(--accent-2));
  display:grid;
  place-items:center;
  font-weight:700;
  color:#05202b;
  font-family:var(--mono);
  font-size:20px;
  box-shadow:0 6px 30px rgba(2,6,23,0.6);
}
.title{
  line-height:1;
}
.title h1{
  margin:0;
  font-size:20px;
  letter-spacing: -0.3px;
}
.title p{
  margin:4px 0 0 0;
  color:var(--muted);
  font-size:13px;
}
.meta{
  display:flex;
  gap:8px;
  margin-top:18px;
  flex-wrap:wrap;
}
.badge{
  background:var(--glass);
  border:1px solid var(--border);
  padding:8px 12px;
  border-radius:999px;
  color:var(--muted);
  font-size:13px;
}
.grid{
  display:grid;
  grid-template-columns: 1fr 360px;
  gap:20px;
  align-items:start;
}
@media (max-width:980px){
  .grid{grid-template-columns:1fr; padding-bottom:40px}
  .header{align-items:flex-start}
}
.card{
  background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
  border-radius:var(--radius);
  padding:22px;
  border:1px solid var(--border);
  box-shadow: 0 10px 30px rgba(2,6,23,0.6);
}
.sidebar .card{position:sticky; top:28px}
.section-title{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-bottom:14px;
}
.h{
  margin:0;
  font-size:16px;
}
.lead{color:var(--muted); font-size:14px; margin-bottom:18px}
.list{
  display:grid;
  gap:12px;
}
.item{
  background:linear-gradient(90deg, rgba(255,255,255,0.012), transparent);
  padding:12px;
  border-radius:10px;
  border:1px solid rgba(255,255,255,0.02);
}
.item h3{margin:0;font-size:14px}
.item p{margin:6px 0 0 0;color:var(--muted);font-size:13px}
.kv{display:flex;gap:10px;flex-wrap:wrap;margin-top:12px}
.kv span{font-size:13px;color:var(--muted)}
.kv strong{color:#dffcf0}
.tasks{list-style:none;padding:0;margin:8px 0 0 0;display:grid;gap:8px}
.tasks li{display:flex;gap:10px;align-items:flex-start}
.dot{width:10px;height:10px;border-radius:50%;background:var(--accent);margin-top:6px;flex-shrink:0}
.small{font-size:13px;color:var(--muted)}
.code{
  background:rgba(255,255,255,0.02);
  border:1px solid rgba(255,255,255,0.03);
  padding:12px;
  border-radius:8px;
  font-family:var(--mono);
  font-size:13px;
  color:#bfeadf;
  overflow:auto;
}
.footer{
  margin-top:18px;
  display:flex;
  justify-content:space-between;
  gap:12px;
  align-items:center;
  color:var(--muted);
  font-size:13px;
  flex-wrap:wrap;
}
.cta{
  background:linear-gradient(90deg,var(--accent),var(--accent-2));
  color:#042b2b;
  padding:10px 14px;
  border-radius:10px;
  font-weight:700;
  border:none;
  cursor:pointer;
  text-decoration:none;
  display:inline-block;
}
.note{
  background:linear-gradient(180deg, rgba(255,255,255,0.01), transparent);
  border-radius:10px;
  padding:12px;
  color:var(--muted);
  font-size:13px;
}
.header-extra{display:flex;gap:10px;align-items:center;margin-left:auto}
.version{background:rgba(255,255,255,0.02);padding:6px 10px;border-radius:8px;color:var(--muted);font-size:13px}
.highlight{color:var(--accent-2);font-weight:600}
.buttons{display:flex;gap:10px;flex-wrap:wrap}
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo">ORD</div>
      <div class="title">
        <h1>Case Técnico — Desenvolvedor Back-end (Laravel)</h1>
        <p>Implementação de módulo de Orders para frontend Vue/Quasar • API segura, testável e preparada para produção</p>
        <div class="meta">
          <div class="badge">Prazo sugerido: 72h</div>
          <div class="badge">Esforço estimado: 6–8 horas</div>
          <div class="badge">Laravel 12 • Sanctum • Queues</div>
        </div>
      </div>
      <div class="header-extra">
        <div class="version">Versão do case</div>
      </div>
    </div>

    <div class="grid">
      <main class="card">
        <div class="section-title">
          <h2 class="h">Contexto</h2>
        </div>
        <p class="lead">Sua empresa precisa desenvolver um módulo de pedidos (Orders) em uma aplicação Laravel que será consumido por um frontend Vue/Quasar. Esse módulo deve permitir cadastro de pedidos, consulta paginada, e disparo de um job assíncrono para enviar e-mail de confirmação. O sistema deve ser seguro, escalável e preparado para produção.</p>

        <div class="section-title" style="margin-top:12px">
          <h3 class="h">Tarefas</h3>
        </div>

        <div class="list">
          <div class="item">
            <h3>Modelagem e Eloquent</h3>
            <p>Criação dos models <strong>Order</strong> e <strong>OrderItem</strong> com relacionamentos corretos e cálculo automático do <em>total_value</em>.</p>
            <div class="kv">
              <span class="small">Relacionamentos: <strong>Order hasMany OrderItem</strong>, <strong>OrderItem belongsTo Order</strong></span>
            </div>
          </div>

          <div class="item">
            <h3>API REST</h3>
            <p>Endpoints RESTful para criação, listagem paginada (filtro por status) e detalhes de pedidos. Versão da API: <strong>/api/v1</strong>.</p>
            <ul class="tasks">
              <li><div class="dot"></div><div><strong>POST /api/v1/orders</strong> — criar pedido com itens</div></li>
              <li><div class="dot"></div><div><strong>GET /api/v1/orders</strong> — listagem paginada e filtro</div></li>
              <li><div class="dot"></div><div><strong>GET /api/v1/orders/{id}</strong> — detalhes do pedido</div></li>
            </ul>
          </div>

          <div class="item">
            <h3>Jobs & Queues</h3>
            <p>Ao criar pedido, disparar job <strong>SendOrderConfirmation</strong> para envio de e-mail simulado via log. Configurar retries, backoff e timeout.</p>
            <div class="kv"><span class="small">Recomendado: QUEUE_CONNECTION=database / redis para produção</span></div>
          </div>

          <div class="item">
            <h3>Segurança</h3>
            <p>Autenticação com <strong>Laravel Sanctum</strong>, rate limiting, proteção de endpoints e policies quando necessário.</p>
          </div>

          <div class="item">
            <h3>Extra (opcional)</h3>
            <p>Endpoint de cancelamento idempotente e erros padronizados com <strong>code, message, traceId</strong>.</p>
          </div>
        </div>

        <div style="margin-top:18px" class="section-title">
          <h3 class="h">O que será avaliado</h3>
        </div>

        <div class="list">
          <div class="item">
            <h3>Estruturação do código</h3>
            <p class="small">Organização em camadas, uso de Resources, Requests, Observers e Jobs.</p>
          </div>
          <div class="item">
            <h3>Uso correto do Eloquent</h3>
            <p class="small">Relacionamentos, mutators, observers para cálculos automáticos.</p>
          </div>
          <div class="item">
            <h3>Boas práticas de API</h3>
            <p class="small">Validação, versionamento, paginação, mensagens padronizadas e docs claros.</p>
          </div>
          <div class="item">
            <h3>Segurança e filas</h3>
            <p class="small">Sanctum, policies e configuração de filas para produção.</p>
          </div>
        </div>

        <div style="margin-top:18px" class="section-title">
          <h3 class="h">Prazo de entrega</h3>
        </div>
        <div class="item">
          <p class="small">O prazo sugerido é de até <strong>72 horas</strong>. Entrega esperada: código funcional, README com instruções e testes básicos.</p>
        </div>

        <div class="footer">
          <div class="note">Dica: inclua testes Feature básicos e um README detalhado para avaliação.</div>
          <div class="buttons">
            <button class="cta" onclick="window.print()">Imprimir / Exportar</button>
            <a class="cta" href="/README" target="_blank">Leia-me</a>
            <a class="cta" href="/docs/Postman.pdf" target="_blank">PDF Postman</a>
          </div>
        </div>
      </main>

      <aside class="sidebar">
        <div class="card">
          <div class="section-title">
            <h4 class="h">Resumo Rápido</h4>
          </div>
          <p class="lead">Um módulo Orders com autenticação, filas e endpoints RESTful, pronto para integração com Vue/Quasar.</p>

          <div style="margin-top:12px">
            <h4 class="small">Principais entregáveis</h4>
            <ul class="tasks" style="margin-top:8px">
              <li><div class="dot"></div><div>Models: Order, OrderItem</div></li>
              <li><div class="dot"></div><div>API: create, list, show, cancel</div></li>
              <li><div class="dot"></div><div>Job: SendOrderConfirmation</div></li>
              <li><div class="dot"></div><div>Auth: Sanctum + rate limit</div></li>
            </ul>
          </div>

          <div style="margin-top:14px">
            <h4 class="small">Comandos úteis</h4>
            <div class="code">php artisan migrate<br>php artisan queue:work --queue=emails,default<br>php artisan tinker</div>
          </div>

          <div style="margin-top:14px">
            <h4 class="small">Endpoints de exemplo</h4>
            <div class="code">GET /api/v1/orders<br>POST /api/v1/orders<br>GET /api/v1/orders/{id}<br>POST /api/v1/orders/{id}/cancel</div>
          </div>
        </div>

        <div style="margin-top:16px" class="card">
          <h4 class="h">Notas</h4>
          <p class="small">Recomenda-se usar SQLite para desenvolvimento e Redis ou database para filas em produção. Documente no README como rodar localmente.</p>
        </div>
      </aside>
    </div>
  </div>
</body>
</html>
