LATEX=latex
BIBTEX=bibtex

all: pdf
	xpdf dp.pdf &

pdf : dvi
	dvipdf dp.dvi

dvi : dp_bibliography.bib dp_cd.tex dp_instalace.tex dp_macro.tex dp_ocekavame.tex dp_pojmy.tex dp_popis.tex dp_pouziti.tex dp_testovani.tex dp.tex dp_udrzba.tex dp_vysilani.tex
	${LATEX} dp
	${BIBTEX} dp
	${LATEX} dp
	${LATEX} dp

clean:  
	rm -f *.aux
	rm -f dp.bbl
	rm -f dp.blg
	rm -f dp.dvi
	rm -f dp.lof
	rm -f dp.log
	rm -f dp.lot
	rm -f dp.toc
#	rm -f dp.pdf
