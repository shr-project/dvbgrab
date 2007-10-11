LATEX=pdflatex

all: pdf
	xpdf dp_en.pdf &

pdf : dvi
#	dvipdf dp.dvi

dvi : dp_instalace.tex dp_macro.tex dp_pouziti.tex dp_en.tex dp_udrzba.tex
	${LATEX} dp_en

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
