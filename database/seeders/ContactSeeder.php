<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();

        $messages = [
            [
                'assunto' => 'Problema com a entrega',
                'mensagem' => 'Olá, fiz uma compra há 5 dias e ainda não recebi o produto. O prazo informado era de 3 dias úteis. Podem verificar o status da entrega?',
            ],
            [
                'assunto' => 'Dúvida sobre pagamento',
                'mensagem' => 'Gostaria de saber quais formas de pagamento são aceitas na plataforma. Vocês aceitam PIX ou boleto bancário?',
            ],
            [
                'assunto' => 'Produto com defeito',
                'mensagem' => 'Recebi um produto que veio com defeito de fábrica. A tela está com um risco grande. Como faço para solicitar a troca ou reembolso?',
            ],
            [
                'assunto' => 'Cancelamento de compra',
                'mensagem' => 'Preciso cancelar uma compra que fiz hoje. O pedido ainda não foi enviado. Podem me ajudar com o cancelamento e estorno do valor?',
            ],
            [
                'assunto' => 'Sugestão de melhoria',
                'mensagem' => 'Seria muito útil se vocês adicionassem um filtro de preço na página de produtos. Facilitaria bastante a busca por itens dentro do meu orçamento.',
            ],
            [
                'assunto' => 'Conta bloqueada',
                'mensagem' => 'Minha conta foi bloqueada sem motivo aparente. Não consigo acessar meus pedidos nem meu saldo. Podem verificar o que aconteceu?',
            ],
            [
                'assunto' => 'Elogio ao atendimento',
                'mensagem' => 'Quero parabenizar a equipe pelo excelente atendimento que recebi na minha última compra. O vendedor foi muito atencioso e o produto chegou antes do prazo!',
            ],
            [
                'assunto' => 'Dúvida sobre saldo',
                'mensagem' => 'Vendi um produto na plataforma mas o saldo ainda não foi creditado na minha conta. Já fazem 2 dias desde a confirmação da venda. Qual o prazo para o crédito?',
            ],
            [
                'assunto' => 'Reclamação de vendedor',
                'mensagem' => 'Um vendedor está anunciando produtos falsificados como originais. Isso é contra as regras da plataforma. Gostaria de reportar essa situação.',
            ],
            [
                'assunto' => 'Problema no cadastro',
                'mensagem' => 'Estou tentando atualizar meu endereço de entrega mas o sistema não salva as alterações. Já tentei em diferentes navegadores e o problema persiste.',
            ],
        ];

        foreach ($messages as $index => $msg) {
            $user = $users->count() > 0 ? $users->random() : null;
            $daysAgo = rand(1, 30);

            Contact::create([
                'nome' => $user?->nome ?? fake('pt_BR')->name(),
                'email' => $user?->email ?? fake('pt_BR')->safeEmail(),
                'assunto' => $msg['assunto'],
                'mensagem' => $msg['mensagem'],
                'user_id' => $user?->id,
                'resposta' => $index < 3 ? 'Obrigado pelo contato. Estamos analisando sua solicitação e retornaremos em breve.' : null,
                'respondido_em' => $index < 3 ? now()->subDays($daysAgo - 1) : null,
                'created_at' => now()->subDays($daysAgo),
                'updated_at' => now()->subDays($daysAgo),
            ]);
        }
    }
}
