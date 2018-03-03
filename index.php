<?php
#phpinfo() and exit;


//  TODO - uncomment and continue web frontend development

const DS = DIRECTORY_SEPARATOR; // All folder constants end in a trailing slash /

define('SERVER_ROOT', __DIR__ . DS);  // Set our root folder for the application

const APP_ROOT = SERVER_ROOT;         // I would like to change to only using app_root soon

if (false === (include SERVER_ROOT . 'Data/Vendors/autoload.php')) {     // Load the autoload() for composer dependencies located in the Services folder
    print '<h1>Loading Composer Failed. See Carbonphp.com for documentation.</h1>' and die;     // Composer autoload
}
/*
$schedule = [
    'groups' => [],
    'people' => [
        'Abraham Lincoln' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Mother Teresa' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'John F. Kennedy' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Martin Luther King' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Nelson Mandela' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Winston Churchill' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Bill Gates' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Muhammad Ali' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Mahatma Gandhi' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Margaret Thatcher' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Charles de Gaulle' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Christopher Columbus' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'George Orwell' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Charles Darwin' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Elvis Presley' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Albert Einstein' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Paul McCartney' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Plato' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Queen Elizabeth II' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Queen Victoria' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'John M Keynes' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Mikhail Gorbachev' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Jawaharlal Nehru' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Leonardo da Vinci' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Louis Pasteur' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Leo Tolstoy' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Pablo Picasso' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Vincent Van Gogh' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Franklin D. Roosevelt' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Pope John Paul II' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Thomas Edison' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Rosa Parks' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Aung San Suu Kyi' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Lyndon Johnson' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Ludwig Beethoven' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Oprah Winfrey' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Indira Gandhi' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Eva Peron' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Benazir Bhutto' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Desmond Tutu' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Dalai Lama' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Walt Disney' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Neil Armstrong' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Donald Trump' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Peter Sellers' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Barack Obama' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Malcolm X' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'J.K.Rowling' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Richard Branson' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Pele' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Angelina Jolie' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Jesse Owens' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Ernest Hemingway' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'John Lennon' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Henry Ford' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Haile Selassie' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Joseph Stalin' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Lord Baden Powell' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Michael Jordon' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'George Bush Jnr' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Vladimir Lenin' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Ingrid Bergman' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Fidel Castro' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Oscar Wilde' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Coco Chanel' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Pope Francis' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Amelia Earhart' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Adolf Hitler' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Sting' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Mary Magdalene' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Alfred Hitchcock' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Michael Jackson' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Madonna' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Mata Hari' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Cleopatra' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Grace Kelly' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Steve Jobs' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Ronald Reagan' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Lionel Messi' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Babe Ruth' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Bob Geldof' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Leon Trotsky' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Roger Federer' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Sigmund Freud' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Woodrow Wilson' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Mao Zedong' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Katherine Hepburn' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Audrey Hepburn' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'David Beckham' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Tiger Woods' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Usain Bolt' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Carl Lewis' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Prince Charles' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Jacqueline Kennedy Onassis' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'C.S. Lewis' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Billie Holiday' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'J.R.R. Tolkien' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Billie Jean King' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
        'Anne Frank' => [
            'pos_pref' => [],
            'groups' => [],
            'availability' => [
                'cant_work' => [],
                'time_pref' => [],
                'pref_not' => []
            ],
        ],
    ]
];
*/
//var_dump($shed);

#print json_encode($schedule);

$app = new Carbon\Carbon('Application/Config/Config.php');

/** At one point I returned the invocation of $app to show that
 * the application will not exit on completion, but rather return
 * back to this index file. This means you can still execute code
 * after $app(); I stopped returning the __invoke() because if false
 * is returned, the index will re-execute. This turns very bad quickly.
 */

$app();


return true;


