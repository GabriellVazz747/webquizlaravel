<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quizData = [
            // --- LARAVEL ---
            [
                'text' => 'No Laravel, qual arquivo é usado para configurar variáveis de ambiente como banco de dados?',
                'options' => [
                    ['text' => 'config.php', 'is_correct' => false],
                    ['text' => '.env', 'is_correct' => true],
                    ['text' => 'env.json', 'is_correct' => false],
                    ['text' => 'database.php', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual comando Artisan é usado para rodar as migrações do banco de dados?',
                'options' => [
                    ['text' => 'php artisan db:seed', 'is_correct' => false],
                    ['text' => 'php artisan make:migration', 'is_correct' => false],
                    ['text' => 'php artisan migrate', 'is_correct' => true],
                    ['text' => 'php artisan db:start', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual a sintaxe correta para exibir uma variável no Blade escapando caracteres HTML?',
                'options' => [
                    ['text' => '{!! $variavel !!}', 'is_correct' => false],
                    ['text' => '{{ $variavel }}', 'is_correct' => true],
                    ['text' => '<?= $variavel ?>', 'is_correct' => false],
                    ['text' => '@echo($variavel)', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'O que é o Eloquent no ecossistema Laravel?',
                'options' => [
                    ['text' => 'Um sistema de template', 'is_correct' => false],
                    ['text' => 'Um ORM (Object-Relational Mapper)', 'is_correct' => true],
                    ['text' => 'Um gerenciador de rotas', 'is_correct' => false],
                    ['text' => 'Um compilador de CSS', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual método do Eloquent é usado para buscar todos os registros de uma tabela?',
                'options' => [
                    ['text' => 'Model::get()', 'is_correct' => false],
                    ['text' => 'Model::all()', 'is_correct' => true],
                    ['text' => 'Model::select()', 'is_correct' => false],
                    ['text' => 'Model::fetch()', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Onde são registradas as rotas de API no Laravel 10/11?',
                'options' => [
                    ['text' => 'routes/web.php', 'is_correct' => false],
                    ['text' => 'routes/api.php', 'is_correct' => true],
                    ['text' => 'app/Http/Routes.php', 'is_correct' => false],
                    ['text' => 'config/routes.php', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual helper do Laravel é usado para depurar variáveis e parar a execução (dump and die)?',
                'options' => [
                    ['text' => 'print_r()', 'is_correct' => false],
                    ['text' => 'stop()', 'is_correct' => false],
                    ['text' => 'dd()', 'is_correct' => true],
                    ['text' => 'debug()', 'is_correct' => false],
                ]
            ],

            // --- VUE.JS ---
            [
                'text' => 'Qual diretiva do Vue cria uma ligação bidirecional (two-way binding) em inputs?',
                'options' => [
                    ['text' => 'v-bind', 'is_correct' => false],
                    ['text' => 'v-model', 'is_correct' => true],
                    ['text' => 'v-on', 'is_correct' => false],
                    ['text' => 'v-input', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'No Vue 3 (Composition API), qual função é usada para criar uma variável reativa?',
                'options' => [
                    ['text' => 'reactiveVar()', 'is_correct' => false],
                    ['text' => 'useState()', 'is_correct' => false],
                    ['text' => 'ref()', 'is_correct' => true],
                    ['text' => 'data()', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual hook de ciclo de vida é chamado logo após o componente ser montado no DOM?',
                'options' => [
                    ['text' => 'created', 'is_correct' => false],
                    ['text' => 'onMounted', 'is_correct' => true],
                    ['text' => 'onUpdated', 'is_correct' => false],
                    ['text' => 'setup', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Como passamos dados de um componente Pai para um Filho?',
                'options' => [
                    ['text' => 'Props', 'is_correct' => true],
                    ['text' => 'Emits', 'is_correct' => false],
                    ['text' => 'Vuex', 'is_correct' => false],
                    ['text' => 'Slots', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Para renderizar uma lista de itens em Vue, usamos:',
                'options' => [
                    ['text' => 'v-list', 'is_correct' => false],
                    ['text' => 'v-for', 'is_correct' => true],
                    ['text' => 'v-each', 'is_correct' => false],
                    ['text' => 'v-loop', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'O que é o Pinia no ecossistema Vue?',
                'options' => [
                    ['text' => 'Uma biblioteca de gráficos', 'is_correct' => false],
                    ['text' => 'O gerenciador de estado oficial', 'is_correct' => true],
                    ['text' => 'Um plugin de validação', 'is_correct' => false],
                    ['text' => 'Um framework CSS', 'is_correct' => false],
                ]
            ],

            // --- JAVASCRIPT & WEB ---
            [
                'text' => 'Qual método de array no JS cria um novo array transformando cada elemento?',
                'options' => [
                    ['text' => 'filter()', 'is_correct' => false],
                    ['text' => 'forEach()', 'is_correct' => false],
                    ['text' => 'map()', 'is_correct' => true],
                    ['text' => 'reduce()', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual palavra-chave declara uma variável que não pode ser reatribuída no JS?',
                'options' => [
                    ['text' => 'var', 'is_correct' => false],
                    ['text' => 'let', 'is_correct' => false],
                    ['text' => 'const', 'is_correct' => true],
                    ['text' => 'static', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual código HTTP indica "Não Encontrado"?',
                'options' => [
                    ['text' => '200', 'is_correct' => false],
                    ['text' => '500', 'is_correct' => false],
                    ['text' => '404', 'is_correct' => true],
                    ['text' => '401', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'O que significa JSON?',
                'options' => [
                    ['text' => 'JavaScript Object Notation', 'is_correct' => true],
                    ['text' => 'Java Source Object Network', 'is_correct' => false],
                    ['text' => 'JavaScript Online Notation', 'is_correct' => false],
                    ['text' => 'Java Standard Output Node', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual a função do comando "npm install"?',
                'options' => [
                    ['text' => 'Iniciar o servidor', 'is_correct' => false],
                    ['text' => 'Baixar dependências listadas no package.json', 'is_correct' => true],
                    ['text' => 'Criar um novo projeto', 'is_correct' => false],
                    ['text' => 'Compilar o código', 'is_correct' => false],
                ]
            ],

            // --- PHP GERAL ---
            [
                'text' => 'Como iniciamos um bloco de código PHP?',
                'options' => [
                    ['text' => '<php>', 'is_correct' => false],
                    ['text' => '<?php', 'is_correct' => true],
                    ['text' => '<%', 'is_correct' => false],
                    ['text' => '<?', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual símbolo é usado para acessar propriedades de um objeto em PHP?',
                'options' => [
                    ['text' => '.', 'is_correct' => false],
                    ['text' => '->', 'is_correct' => true],
                    ['text' => '::', 'is_correct' => false],
                    ['text' => '=>', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual superglobal do PHP contém dados enviados via formulário POST?',
                'options' => [
                    ['text' => '$_GET', 'is_correct' => false],
                    ['text' => '$_SERVER', 'is_correct' => false],
                    ['text' => '$_POST', 'is_correct' => true],
                    ['text' => '$_FILES', 'is_correct' => false],
                ]
            ],
             [
                'text' => 'Qual é o gerenciador de dependências padrão do PHP?',
                'options' => [
                    ['text' => 'NPM', 'is_correct' => false],
                    ['text' => 'Composer', 'is_correct' => true],
                    ['text' => 'Pip', 'is_correct' => false],
                    ['text' => 'Gem', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Para verificar se uma variável existe e não é nula em PHP, usamos:',
                'options' => [
                    ['text' => 'isset()', 'is_correct' => true],
                    ['text' => 'empty()', 'is_correct' => false],
                    ['text' => 'is_null()', 'is_correct' => false],
                    ['text' => 'exist()', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Qual o comando Git para enviar alterações para o repositório remoto?',
                'options' => [
                    ['text' => 'git pull', 'is_correct' => false],
                    ['text' => 'git commit', 'is_correct' => false],
                    ['text' => 'git push', 'is_correct' => true],
                    ['text' => 'git fetch', 'is_correct' => false],
                ]
            ],
            [
                'text' => 'Em CSS, qual propriedade altera a cor do texto?',
                'options' => [
                    ['text' => 'font-color', 'is_correct' => false],
                    ['text' => 'text-color', 'is_correct' => false],
                    ['text' => 'color', 'is_correct' => true],
                    ['text' => 'background-color', 'is_correct' => false],
                ]
            ]
        ];

        foreach ($quizData as $data) {
            $question = Question::create(['text' => $data['text']]);
            $question->options()->createMany($data['options']);
        }
    }
}
