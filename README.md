cd HistoryLogBundle/
git init
touch README.md
nano composer.json

{
    "name" : "cf/historylogbundle",
    "description" : "A History Log bundle",
    "type" : "symfony-bundle",
    "authors" : [{
        "name" : "Jose Carlos Ramos Carmenates",
        "email" : "jramoscarmenates@gmail.com"
    }],
    "keywords" : [
        "demo bundle"
    ],
    "license" : [
        "MIT"
    ],
    "require" : {
    },
    "autoload" : {
        "psr-0" : {
            "Cf\\HistoryLogBundle" : ""
        }
    },
    "target-dir" : "Cf/HistoryLogBundle",
    "repositories" : [{
    }],
    "extra" : {
    "branch-alias" : {
            "dev-master" : "1.0.0"
        }
    }
}

git add .
git commit -m "Upload all to git"
git remote add origin-vm http://gitlab:8080/josecarlos/historylogbundle.git
git push -u origin-vm master
