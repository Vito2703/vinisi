from flask import Flask, render_template, abort, request, redirect, url_for, session, jsonify, make_response
import json
import webbrowser
import threading
import os
import random


app = Flask(__name__)
app.secret_key = 'chave-super-secreta'  # altera isto em produção!

with open('vinhos.json', encoding='utf-8') as f:
    vinhos = json.load(f)

@app.route("/")
def index():
    tipo_filtro = request.args.get('tipo', '').lower()
    regiao_filtro = request.args.get('regiao', '').lower()
    ano_filtro = request.args.get('ano', '')
    casta_filtro = request.args.get('casta', '').lower()
    preco_min = request.args.get('preco_min', '')
    preco_max = request.args.get('preco_max', '')

    vinhos_filtrados = vinhos

    if tipo_filtro:
        vinhos_filtrados = [v for v in vinhos_filtrados if v['tipo'].lower() == tipo_filtro]
    if regiao_filtro:
        vinhos_filtrados = [v for v in vinhos_filtrados if v['regiao'].lower() == regiao_filtro]
    if ano_filtro.isdigit():
        vinhos_filtrados = [v for v in vinhos_filtrados if v['ano'] == int(ano_filtro)]
    if casta_filtro:
        vinhos_filtrados = [
            v for v in vinhos_filtrados
            if casta_filtro in [c.strip().lower() for c in v['castas'].split(',')]
        ]
    if preco_min.replace('.', '', 1).isdigit():
        preco_min_val = float(preco_min)
        vinhos_filtrados = [v for v in vinhos_filtrados if v['preco'] >= preco_min_val]
    if preco_max.replace('.', '', 1).isdigit():
        preco_max_val = float(preco_max)
        vinhos_filtrados = [v for v in vinhos_filtrados if v['preco'] <= preco_max_val]

    vinhos_filtrados = [v for v in vinhos_filtrados if v['stock'] > 0]

    tipos = sorted(set(v['tipo'] for v in vinhos))
    regioes = sorted(set(v['regiao'] for v in vinhos))
    anos = sorted(set(v['ano'] for v in vinhos), reverse=True)

    castas_set = set()
    for v in vinhos:
        for c in v['castas'].split(','):
            castas_set.add(c.strip())
    castas = sorted(castas_set)

    quantidade_carrinho = len(session.get('carrinho', []))
    user = session.get('usuario')

    return render_template(
        "index.html",
        vinhos=vinhos_filtrados,
        filtros={
            'tipo': tipo_filtro,
            'regiao': regiao_filtro,
            'ano': ano_filtro,
            'casta': casta_filtro,
            'preco_min': preco_min,
            'preco_max': preco_max
        },
        opcoes={
            'tipos': tipos,
            'regioes': regioes,
            'anos': anos,
            'castas': castas
        },
        quantidade_carrinho=quantidade_carrinho,
        user=user
    )

@app.route("/vinho/<codigo>")
def detalhe_vinho(codigo):
    vinho = next((v for v in vinhos if v['codigo'] == codigo), None)
    if vinho is None:
        abort(404)
    quantidade_carrinho = len(session.get('carrinho', []))
    user = session.get('usuario')
    return render_template("detalhe.html", vinho=vinho, quantidade_carrinho=quantidade_carrinho, user=user)

@app.route("/adicionar_carrinho/<codigo>", methods=['POST'])
def adicionar_carrinho(codigo):
    vinho = next((v for v in vinhos if v['codigo'] == codigo), None)
    if vinho is None:
        return jsonify({'erro': 'Vinho não encontrado'}), 404

    data = request.get_json(force=True, silent=True)
    if data is None:
        return jsonify({'erro': 'Pedido JSON inválido ou ausente'}), 400

    quantidade = data.get('quantidade', 1)
    try:
        quantidade = int(quantidade)
    except (ValueError, TypeError):
        return jsonify({'erro': 'Quantidade inválida'}), 400

    if quantidade < 1:
        return jsonify({'erro': 'Quantidade inválida'}), 400

    if vinho['stock'] < quantidade:
        return jsonify({'erro': f'Stock insuficiente: disponível {vinho["stock"]}'}), 400

    if 'carrinho' not in session:
        session['carrinho'] = []

    session['carrinho'].extend([codigo] * quantidade)
    session.modified = True

    vinho['stock'] -= quantidade

    return jsonify({
        'quantidade': len(session['carrinho']),
        'stock': vinho['stock']
    })

@app.route("/carrinho")
def ver_carrinho():
    carrinho_codigos = session.get('carrinho', [])
    vinhos_carrinho = [v for v in vinhos if v['codigo'] in carrinho_codigos]

    contagem = {}
    for codigo in carrinho_codigos:
        contagem[codigo] = contagem.get(codigo, 0) + 1

    total = sum(v['preco'] * contagem[v['codigo']] for v in vinhos_carrinho)
    user = session.get('usuario')

    return render_template("carrinho.html", vinhos=vinhos_carrinho, contagem=contagem, total=total, user=user)

@app.route("/limpar_carrinho")
def limpar_carrinho():
    carrinho_codigos = session.get('carrinho', [])
    contagem = {}
    for codigo in carrinho_codigos:
        contagem[codigo] = contagem.get(codigo, 0) + 1

    for codigo, quantidade in contagem.items():
        vinho = next((v for v in vinhos if v['codigo'] == codigo), None)
        if vinho:
            vinho['stock'] += quantidade

    session.pop('carrinho', None)
    session.modified = True

    return redirect(url_for('index'))

@app.route("/remover_quantidade/<codigo>", methods=['POST'])
def remover_quantidade(codigo):
    try:
        qtd_remover = int(request.form.get('quantidade', 1))
        if qtd_remover < 1:
            raise ValueError()
    except:
        return redirect(url_for('ver_carrinho'))

    carrinho = session.get('carrinho', [])
    removidos = 0

    while codigo in carrinho and removidos < qtd_remover:
        carrinho.remove(codigo)
        removidos += 1

    vinho = next((v for v in vinhos if v['codigo'] == codigo), None)
    if vinho:
        vinho['stock'] += removidos

    session['carrinho'] = carrinho
    session.modified = True

    return redirect(url_for('ver_carrinho'))

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        username = request.form.get('username')
        password = request.form.get('password')

        if username == 'admin' and password == '1234':
            session['usuario'] = username
            return redirect(url_for('index'))
        else:
            erro = "Utilizador ou palavra-passe inválidos."
            return render_template("login.html", erro=erro)

    return render_template("login.html")

@app.route("/logout")
def logout():
    session.pop('usuario', None)
    return redirect(url_for('index'))

@app.route("/finalizar_compra", methods=["GET", "POST"])
def finalizar_compra():
    carrinho_codigos = session.get('carrinho', [])
    if not carrinho_codigos:
        return render_template("erro.html", mensagem="Carrinho vazio.")

    vinhos_carrinho = [v for v in vinhos if v['codigo'] in carrinho_codigos]
    contagem = {}
    for codigo in carrinho_codigos:
        contagem[codigo] = contagem.get(codigo, 0) + 1

    total = sum(v['preco'] * contagem[v['codigo']] for v in vinhos_carrinho)
    transportadoras = ["ViniExpress", "RapidVinhos", "CastaTrans"]

    if request.method == "POST":
        nome = request.form.get("nome")
        morada = request.form.get("morada")
        horario = request.form.get("horario")
        transportadora = request.form.get("transportadora")

        numero_encomenda = f"VS{len(carrinho_codigos)}{hash(nome) % 10000}"

        session["dados_encomenda"] = {
            "nome": nome,
            "morada": morada,
            "horario": horario,
            "transportadora": transportadora,
            "total": total,
            "vinhos": [
                {"nome": v["nome"], "quantidade": contagem[v["codigo"]], "preco": v["preco"]}
                for v in vinhos_carrinho
            ]
        }

        session["numero_encomenda"] = numero_encomenda
        session.modified = True

        # redirecionar para pagina de pagamento
        return redirect(url_for("pagamento"))

    return render_template("finalizar_compra.html",
                           vinhos=vinhos_carrinho,
                           contagem=contagem,
                           total=total,
                           transportadoras=transportadoras)

@app.route("/encomenda_confirmada")
def encomenda_confirmada():
    dados = session.get("dados_encomenda", {})
    numero = session.get("numero_encomenda", "0000")
    return render_template("encomenda_confirmada.html", numero=numero, dados=dados)



@app.route("/gerar_pdf")
def gerar_pdf():
    dados = session.get("dados_encomenda", {})
    numero = session.get("numero_encomenda", "0000")

    if not dados:
        return redirect(url_for("index"))

    html = render_template("pdf_encomenda.html", numero=numero, dados=dados)
    response = make_response(html)

    response.headers["Content-Type"] = "application/octet-stream"
    response.headers["Content-Disposition"] = f"attachment; filename=encomenda_{numero}.html"

    # Limpa o carrinho e dados da encomenda depois de gerar o PDF
    session.pop("carrinho", None)
    session.pop("dados_encomenda", None)
    session.pop("numero_encomenda", None)
    session.modified = True

    return response



@app.route("/pagamento", methods=["GET", "POST"])
def pagamento():
    dados = session.get("dados_encomenda")
    if not dados:
        return redirect(url_for("index"))

    total = dados.get("total", 0.0)

    if request.method == "POST":
        metodo = request.form.get("metodo_pagamento")

        # Validações simples dependendo do método
        if metodo == "cartao":
            numero = request.form.get("numero_cartao", "").replace(" ", "")
            validade = request.form.get("validade", "")
            cvv = request.form.get("cvv", "")
            nome_cartao = request.form.get("nome_cartao", "")

            if not (numero.isdigit() and len(numero) == 16 and
                    len(cvv) == 3 and cvv.isdigit() and
                    "/" in validade and nome_cartao.strip()):
                erro = "Dados do cartão inválidos."
                return render_template("pagamento.html", dados=dados, total=total, erro=erro)

        elif metodo == "mbway":
            telefone = request.form.get("telefone_mbway", "")
            if not (telefone.isdigit() and len(telefone) >= 9):
                erro = "Número de telemóvel MB Way inválido."
                return render_template("pagamento.html", dados=dados, total=total, erro=erro)

        elif metodo == "paypal":
            email = request.form.get("email_paypal", "")
            if "@" not in email or "." not in email:
                erro = "Email PayPal inválido."
                return render_template("pagamento.html", dados=dados, total=total, erro=erro)

        else:
            erro = "Método de pagamento inválido."
            return render_template("pagamento.html", dados=dados, total=total, erro=erro)

        # Simular pagamento aprovado
        # Baixar stock dos vinhos conforme quantidades
        vinhos_carrinho = dados.get("vinhos", [])
        for item in vinhos_carrinho:
            vinho = next((v for v in vinhos if v["nome"] == item["nome"]), None)
            if vinho:
                vinho["stock"] -= item["quantidade"]

        # Limpar carrinho
        session.pop("carrinho", None)
        session.modified = True

        # Guardar método para mostrar na confirmação
        session["metodo_pagamento"] = metodo

        return redirect(url_for("encomenda_confirmada"))

    return render_template("pagamento.html", dados=dados, total=total)



def open_browser():
    webbrowser.open_new("http://127.0.0.1:5000")

if __name__ == "__main__":
    if os.environ.get("WERKZEUG_RUN_MAIN") == "true":
        threading.Timer(1, open_browser).start()
    app.run(debug=True)
