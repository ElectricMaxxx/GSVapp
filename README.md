GSV Application
=======================

Einleitung
------------

later

Annahmen
--------

Um die Aufgabe durch zu führen haben ich folgende Annahemen erst einmal voraus gesetzt:
- es muss der Kasse (`CashBox`) ein Anfangsbestand über eine sog. `Donation` zugeführt werden, da diese sonst
immer im Rückstand ist. Diese Donation kann immer wieder wiederholt werden, wenn z.B. Rückstände
nicht faktoriert werden können. Die Kasse selbst wird nie persistiert, Sie wird immer aus den Werten aus Events
und Donations berechnet.
- Ein Mitglied nimmt an einer Veranstaltung (`Event`) teil indem er entweder einen Verzehr bucht, oder dessen Verzehr
durch den Grillmeister nachgetragen wird.
- Einem Verzehr (`Consumption`) kann eine Mahlzeit (`Meal` kann einzeln definiert werde, hier erst einmal Steak) mit einer Quantität
zugeordnet werden. Der Preis wird wird immer beim buchen von der Mahlzeit übernommen und kann auch nicht nachträglich
im Verzehr geändert werden. Der Preis der Mahlzeit jedoch schon.

Installation
------------

later