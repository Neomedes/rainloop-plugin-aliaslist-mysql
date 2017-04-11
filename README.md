For an englisch version, see below.

# rainloop-plugin-aliaslist-mysql

Dies ist ein [RainLoop](http://rainloop.net/)-Plugin, mit welchem man alternative E-Mail-Adressen für sein eigenes Konto anlegen und bearbeiten kann. Die verfügbaren Aliase müssen dafür in einer MySQL-Datenbank gespeichert werden, welche vom RainLoop-Server aus erreichbar ist.

Um dieses Repository zu klonen, muss man **das Ziel-Verzeichnis umbenennen**, da das Plugin ansonsten *nicht funktioniert*:

```Bash
    git clone https://github.com/Neomedes/rainloop-plugin-aliaslist-mysql.git aliaslist-mysql
```

Dieses Plugin wurde entwickelt um mit dem eigenen Mailserver zu funktionieren, welcher mit [dieser fantastischen Anleitung](https://thomas-leister.de/mailserver-unter-ubuntu-16.04/) erstellt wurde. Dennoch übernehme ich keine Gewähr dafür, dass die Daten eines so oder anders aufgesetzten Servers durch dieses Plugin nicht beschädigt werden. Die Verwendung erfolgt auf eigene Gefahr, jedoch helfe ich sofern ich kann gerne bei der Behebung des Problems soweit es dieses Plugin betrifft.

## English version

A [RainLoop](http://rainloop.net/) plugin that adds support for viewing and setting e-mail address aliases for the connected account. Those aliases must be saved within a MySQL database on a server connected to the one running RainLoop.

For cloning, it is necessary to **rename the destination folder** for the project, or else *the plugin won't work*:

```Bash
    git clone https://github.com/Neomedes/rainloop-plugin-aliaslist-mysql.git aliaslist-mysql
```

This plugin is designed to work with the server created with this [fantastic mailserver setup guide](https://thomas-leister.de/mailserver-unter-ubuntu-16.04/). Yet a cannot guarantee that this plugin doesn't compromise the rainloop installation, the data stored on the MySQL-Server or anything else. Nevertheless I try to help and fix these problems as they are concerning this plugin.
