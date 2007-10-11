LATEX=pdflatex

all: pdf
	xpdf dp.pdf &

pdf : dvi
#	dvipdf dp.dvi

dvi : dp_instalace.tex dp_macro.tex dp_pouziti.tex dp.tex dp_udrzba.tex
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
	rm -f dp.pdf
	rm -f dp.out
