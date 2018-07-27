# GroupComboSelect Component

Componente para select encadeado de uma estrutura de grafo com diâmetro 3. Vamos definir a estrutura de dados com conceitos de grafo direcionado para melhor entendimento.

Esse é um componente stateless e aceita 5 propriedades:


# PROP data

Essa propriedade é os dados que serão renderizados nos inputs, esse data é um objeto javascript que pode ser enxergado como um grafo direcionado com diâmetro 3. O diâmetro de um grafo é definido pelo maior distância entre os pares de vértices do grafo. Cada nível desse grafo tem um significado. O primeiro nível é chamado de group, é onde fica o nome dos agrupamentos, o segundo nível é chamado de label, é onde fica os valores dos agrupamentos e o terceiro nível é chamado de values que é os valores encadeado do label.

Segue um exemplo desse grafo na representação JSON:

{ group1:
    { label1: [v1, v2, v3]
    , label2: [v1, v2]
    }
, group2:
    { g2lbael1: [v1]
    }
, group3:
    { g3label1: [v1,v2]
    , g3label2: []
    , g3label3: [v1]
    }
, group4:
    {
    }
}


# PROP mapRender [opcional]

Um objeto javascript com funcoes para mapear a renderização do valor visualizado na interface. Esse mapeamento nao afeta o valor dos dados passado, apenas mapea o valor para mostrar nos inputs. O objeto espera 3 chaves, podendo passar somente aquelas que o usuário achar necessário. Segue abaixo exemplos:

A função de cada chave do objeto receberá uma (Maybe a) como parâmetro, quando no contexto de (Nothing), representa o valor para renderizar o placeholder quando nao for group, para group, representa um valor indefinido. E quando no contexto de (Just a) representa um valor que ira ser renderizado. A assintura da função ficará assim: (Maybe a -> a)

EX1: esse é um exemplo com todas as 3 chaves esperadas pelo objeto e supondo que tenhamos valores como String (Maybe String)
{ group: (ma) => ma.cata({ Nothing: () => "Anonymous group", Just: x => "Group: " + x })
, label: (ma) => ma.cata({ Nothing: () => "Select a label", Just: x => x.trim().replace(/[aeiou]/g, "-") })
, values: (ma) => ma.cata({ Nothing: () => "Select a value", Just: x => "value " + x })
}

EX2: você pode passar apenas os render que interessar sobreescrever
{ label: (ma) => ma.cata({ Nothing: () => "Select a tag name", Just: x => "tag: " + x })
}

EX3: Você pode colocar apenas o placeholder sem precisar mapear:
{ label: (ma) => ma.option("Select a label to see the values")
}


# PROP value

Aqui o componente espera um tipo Value. O tipo Value é um object wrap criado por uma função factory que se encontra em GroupCombo/models/ segue exemplo de como importar esse wrap.

import { Value } from "path/to/GroupCombo/models/"

Value é uma função factory como mencionado antes. Essa função espera um objeto da seguinte forma:
{ type: Maybe a
, label: Maybe a
, values: Maybe a
}

EX1: Value()
EX2: Value({ type: Maybe.Just("custom"), label: Maybe.Just("hg-MGH") })
EX3: Value({ type: Maybe.Just("custom"), label: Maybe.Just("Name"), values: Maybe.Just("hg-MGA") })
EX4: Value({ type: Maybe.Nothing() })
EX5: Value({ type: Maybe.Just("custom"), label: Maybe.Nothing(), values: Maybe.Just([1,2,3]) })


# PROP onChange

Função que ira ser executada quando houver uma seleção. Para essa função será passado um Value com os dados atualizados pela interface, assim o usuário pode simplesmente atualizar seu value que ira passar novamente para o componente se atualizar na interface.

(Value a -> ?)

Como é evento, o retorno dessa função é irrelevante.


# PROP onCreate

Função que será executada quando o botão de create ser clicado, essa função nao recebe nenhum parâmetro já que o controle dos valores esta por conta do usuário e nao do componente (remember: component stateless).


REFERENCIAL TEORICO:
Factory function: https://www.youtube.com/watch?v=ImwrezYhw4w
Union type (entender oq eh Maybe e alguns data struct internos do componente): https://en.wikipedia.org/wiki/Union_type
Grafos: https://pt.wikipedia.org/wiki/Caminho_(teoria_dos_grafos)
